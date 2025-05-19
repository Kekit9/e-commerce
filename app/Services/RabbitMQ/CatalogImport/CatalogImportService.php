<?php

declare(strict_types=1);

namespace App\Services\RabbitMQ\CatalogImport;

use App\Mail\CatalogExported;
use App\Services\RabbitMQ\CatalogImport\Interface\CatalogImportServiceInterface;
use App\Services\RabbitMQ\Interfaces\RabbitMQConnectionServiceInterface;
use Exception;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Str;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;
use PhpAmqpLib\Channel\AMQPChannel;
use RuntimeException;

/**
 * Service for processing catalog exports from RabbitMQ queue
 *
 * Handles incoming catalog export messages, saves them to storage
 * and sends email notifications with download links
 */
class CatalogImportService implements CatalogImportServiceInterface
{
    /**
     * @param LoggerInterface $logger Logger instance for error tracking
     * @param Filesystem $storage Filesystem adapter for storing exports
     * @param Mailer $mailer Mailer service for notifications
     * @param string $adminEmail Administrator email for notifications
     * @param RabbitMQConnectionServiceInterface $rabbitMQConnection RabbitMQ connection service
     */
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly Filesystem $storage,
        private readonly Mailer $mailer,
        private readonly string $adminEmail,
        private readonly RabbitMQConnectionServiceInterface $rabbitMQConnection
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function processExports(): void
    {
        $channel = $this->getChannel();

        try {
            $this->declareQueue($channel);
            $this->setupConsumer($channel);
            $this->startConsuming($channel);
        } finally {
            $channel->close();
        }
    }

    /**
     * Creates and returns RabbitMQ channel from the established connection
     *
     * @return AMQPChannel New channel instance
     *
     * @throws RuntimeException If channel creation fails
     */
    private function getChannel(): AMQPChannel
    {
        return $this->rabbitMQConnection->getConnection()->channel();
    }

    /**
     * Declares the queue for catalog exports
     *
     * @param AMQPChannel $channel RabbitMQ channel
     *
     * @return void
     */
    private function declareQueue(AMQPChannel $channel): void
    {
        $channel->queue_declare(
            queue: 'catalog_export',
            passive: false,
            durable: true,
            exclusive: false,
            auto_delete: false
        );
    }

    /**
     * Sets up message consumer with callback
     *
     * @param AMQPChannel $channel RabbitMQ channel
     *
     * @return void
     */
    private function setupConsumer(AMQPChannel $channel): void
    {
        $callback = function (AMQPMessage $msg) {
            try {
                $this->processMessage($msg);
                $msg->ack();
            } catch (Exception $e) {
                $this->handleProcessingError($e, $msg);
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
    }

    /**
     * Processes incoming message from RabbitMQ
     *
     * @param AMQPMessage $msg Incoming message with catalog data
     *
     * @return void
     *
     * @throws Exception If file operations fail
     */
    private function processMessage(AMQPMessage $msg): void
    {
        $filename = 'catalog_export_' . Str::uuid() . '.csv';
        $filePath = "exports/{$filename}";

        $this->storage->put($filePath, $msg->body);
        $this->sendNotification($filename, $filePath);
    }

    /**
     * Sends email notification with export download link
     *
     * @param string $filename Export filename
     * @param string $filePath Full storage path
     *
     * @return void
     */
    private function sendNotification(string $filename, string $filePath): void
    {
        $this->mailer->to($this->adminEmail)
            ->send(new CatalogExported(
                $filename,
                $this->storage->temporaryUrl($filePath, now()->addHour())
            ));
    }

    /**
     * Handles message processing errors
     *
     * @param Exception $e Caught exception
     * @param AMQPMessage $msg Failed message
     *
     * @return void
     */
    private function handleProcessingError(Exception $e, AMQPMessage $msg): void
    {
        $this->logger->error(__('catalog.processing_failed'), [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);
        $msg->nack();
    }

    /**
     * Starts consuming messages from the queue
     *
     * @param AMQPChannel $channel RabbitMQ channel
     *
     * @return void
     */
    private function startConsuming(AMQPChannel $channel): void
    {
        while ($channel->is_consuming()) {
            $channel->wait();
        }
    }
}
