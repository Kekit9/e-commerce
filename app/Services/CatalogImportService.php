<?php

declare(strict_types=1);

namespace App\Services;

use App\Mail\CatalogExported;
use Exception;
use Illuminate\Support\Str;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Mail\Mailer;
use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * Service class for handling catalog export processing from RabbitMQ to S3
 *
 * This service listens to a RabbitMQ queue for catalog export messages,
 * saves the received CSV data to S3 storage, and notifies administrators
 * via email with a temporary download link.
 */
class CatalogImportService
{
    /**
     * CatalogImportService constructor.
     *
     * @param LoggerInterface $logger
     * @param Filesystem $storage
     * @param Mailer $mailer
     * @param string $adminEmail
     * @param AMQPStreamConnection $connection
     */
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly Filesystem $storage,
        private readonly Mailer $mailer,
        private readonly string $adminEmail,
        private readonly AMQPStreamConnection $connection
    ) {
    }

    /**
     * Processes catalog exports from RabbitMQ queue
     *
     * Listens to the 'catalog_export' queue for incoming messages containing
     * catalog data in CSV format. Each received message is:
     * 1. Saved to S3 storage with a timestamped filename
     * 2. Triggers an email notification to administrators with a temporary download link
     * 3. Acknowledges successful processing or negatively acknowledges on failure
     *
     * @return void
     *
     * @throws Exception If there's an error establishing the RabbitMQ connection
     *
     * @uses AMQPStreamConnection For RabbitMQ connectivity
     * @uses Filesystem For persisting the CSV to S3
     * @uses Mailer For sending email notifications via SES
     * @uses LoggerInterface For error logging
     */
    public function processExports(): void
    {
        $channel = $this->connection->channel();

        $channel->queue_declare(
            queue: 'catalog_export',
            passive: false,
            durable: true,
            exclusive: false,
            auto_delete: false
        );

        $callback = function (AMQPMessage $msg) {
            try {
                $filename = 'catalog_export_' . Str::uuid() . '.csv';
                $filePath = "exports/{$filename}";

                $this->storage->put($filePath, $msg->body);
                $this->mailer->to($this->adminEmail)
                    ->send(new CatalogExported(
                        $filename,
                        $this->storage->temporaryUrl($filePath, now()->addHour())
                    ));

                $msg->ack();
            } catch (Exception $e) {
                $this->logger->error(__('catalog.processing_failed'), [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]);
                $msg->nack();
            }
        };

        $channel->basic_consume(
            queue: 'catalog_export',
            consumer_tag: '',
            no_local: false,
            no_ack: false,
            exclusive: false,
            nowait: false,
            callback: $callback
        );

        while ($channel->is_consuming()) {
            $channel->wait();
        }

        $channel->close();
    }
}
