<?php

namespace App\Services;

use App\Interfaces\CatalogExportRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use League\Csv\CannotInsertRecord;
use League\Csv\Exception;
use League\Csv\Writer;

class CatalogExportService
{
    /**
     * The catalog export repository instance
     *
     * @var CatalogExportRepositoryInterface
     */
    protected CatalogExportRepositoryInterface $catalogExportRepository;
    protected RabbitMQService $rabbitMQService;

    /**
     * CatalogExportService constructor
     *
     * @param CatalogExportRepositoryInterface $catalogExportRepository The catalog export repository
     */
    public function __construct(CatalogExportRepositoryInterface $catalogExportRepository, RabbitMQService $rabbitMQService)
    {
        $this->catalogExportRepository = $catalogExportRepository;
        $this->rabbitMQService = $rabbitMQService;
    }

    public function exportCatalog(): array
    {
        try {
            $products = $this->catalogExportRepository->getProductsForExport();

            if ($products->isEmpty()) {
                throw new \RuntimeException(__('catalog.runtime_error'));
            }

            $csvContent = $this->generateCsvContent($products);

            $this->rabbitMQService->publishToCatalogExportQueue($csvContent);

            return [
                'success' => true,
                'message' => __('currency.queued'),
                'error' => null
            ];

        } catch (\Exception $e) {
            Log::error(__('currency.export_failed') . $e->getMessage());

            return [
                'success' => false,
                'message' => __('currency.export_failed'),
                'error' => config('app.debug') ? $e->getMessage() : null
            ];
        }
    }

    /**
     * Generate CSV content from products
     *
     * @param Collection $products
     *
     * @return string
     *
     * @throws CannotInsertRecord
     * @throws Exception
     */
    public function generateCsvContent(Collection $products): string
    {
        $csv = Writer::createFromString('');
        $csv->insertOne(['ID', 'Name', 'Description', 'Price', 'Maker', 'Services']);

        $products->each(fn($product) => $csv->insertOne([
            $product->id,
            $product->name,
            $product->description,
            $product->price,
            $product->maker?->name ?? 'N/A',
            $product->services->pluck('name')->implode(', ') ?: 'N/A'
        ]));

        return $csv->getContent();
    }
}
