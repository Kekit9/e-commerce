<?php

declare(strict_types=1);

namespace App\Services\RabbitMQ\CatalogExport;

use App\Models\Product;
use App\Repositories\CatalogExport\Interface\CatalogExportRepositoryInterface;
use App\Services\RabbitMQ\CatalogExport\Interface\CatalogExportServiceInterface;
use App\Services\RabbitMQ\RabbitMQService;
use Illuminate\Database\Eloquent\Collection;
use League\Csv\CannotInsertRecord;
use League\Csv\Exception;
use League\Csv\Writer;
use Psr\Log\LoggerInterface;

class CatalogExportService implements CatalogExportServiceInterface
{
    public const NA = 'notApplicable';

    /**
     * CatalogExportService constructor
     *
     * @param CatalogExportRepositoryInterface $catalogExportRepository The catalog export repository
     * @param RabbitMQService $rabbitMQService The RabbitMQ service
     * @param LoggerInterface $logger The logger instance
     */
    public function __construct(
        protected CatalogExportRepositoryInterface $catalogExportRepository,
        protected RabbitMQService $rabbitMQService,
        protected LoggerInterface $logger
    ) {
    }

    /**
     * Export catalog to CSV
     *
     * @return array{
     *     success: bool,
     *     message: string,
     *     error: string|null
     * }
     *
     * @throws Exception
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

            throw new Exception(__('currency.export_failed') . $e->getMessage());
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
