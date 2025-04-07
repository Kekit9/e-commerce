<?php

namespace App\Repositories;

use App\Interfaces\CatalogExportRepositoryInterface;
use App\Models\Product;
use App\Mail\CatalogExported;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use League\Csv\CannotInsertRecord;
use League\Csv\Exception;
use League\Csv\Writer;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class CatalogExportRepository implements CatalogExportRepositoryInterface
{
    /**
     * Export catalog to CSV and publish to RabbitMQ queue
     *
     * @return array
     */
    public function exportCatalogToCsv(): array
    {
        try {
            $products = Product::with(['maker', 'services'])->get();

            if ($products->isEmpty()) {
                throw new \RuntimeException('No products found for export');
            }

            $csvContent = $this->generateCsvContent($products);
            $this->publishToQueue($csvContent);

            return [
                'success' => true,
                'message' => 'Catalog export queued for processing',
                'error' => null
            ];

        } catch (\Exception $e) {
            Log::error('Export failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Export failed: ' . $e->getMessage(),
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ];
        }
    }

    /**
     * Generate CSV content from products
     *
     * @throws CannotInsertRecord
     * @throws Exception
     */
    protected function generateCsvContent($products): string
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

    /**
     * Publish CSV content to RabbitMQ queue
     * @throws \Exception
     */
    protected function publishToQueue(string $csvContent): void
    {
        Log::info('Publishing to RabbitMQ. Content length: '.strlen($csvContent));
        $connection = new AMQPStreamConnection(
            config('rabbitmq.host'),
            config('rabbitmq.port'),
            config('rabbitmq.user'),
            config('rabbitmq.password'),
            config('rabbitmq.vhost')
        );

        $channel = $connection->channel();

        $channel->queue_declare(
            'catalog_export',
            false,
            true,
            false,
            false
        );

        $message = new AMQPMessage($csvContent, [
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]);

        $channel->basic_publish($message, '', 'catalog_export');

        $channel->close();
        $connection->close();
    }
}
