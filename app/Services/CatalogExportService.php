<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\CatalogExportRepositoryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Psr\Log\LoggerInterface;
use League\Csv\CannotInsertRecord;
use League\Csv\Exception;
use League\Csv\Writer;

class CatalogExportService
{
    public const NA = 'notApplicable';

    /**
     * The catalog export repository instance
     *
     * @var CatalogExportRepositoryInterface
     */
    protected CatalogExportRepositoryInterface $catalogExportRepository;

    /**
     * The RabbitMQ service instance
     *
     * @var RabbitMQService
     */
    protected RabbitMQService $rabbitMQService;

    /**
     * The logger instance
     *
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * CatalogExportService constructor
     *
     * @param CatalogExportRepositoryInterface $catalogExportRepository The catalog export repository
     */
    public function __construct(CatalogExportRepositoryInterface $catalogExportRepository, RabbitMQService $rabbitMQService, LoggerInterface $logger)
    {
        $this->catalogExportRepository = $catalogExportRepository;
        $this->rabbitMQService = $rabbitMQService;
        $this->logger = $logger;
    }

    /**
     * Export catalog to CSV
     *
     * @return array{
     *     success: bool,
     *     message: string,
     *     error: string|null
     * }
     */
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
            $this->logger->error(__('currency.export_failed'), [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

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
     * @param Collection<int, Product> $products
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

        $products->each(fn ($product) => $csv->insertOne([
            $product->id,
            $product->name,
            $product->description,
            $product->price,
            $product->maker?->name ?? self::NA,
            $product->services->pluck('name')->implode(', ') ?: self::NA,
        ]));

        return $csv->getContent();
    }
}
