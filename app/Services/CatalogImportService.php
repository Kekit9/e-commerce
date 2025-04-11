<?php

namespace App\Services;

use App\Mail\CatalogExported;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
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
     * @uses Storage For persisting the CSV to S3
     * @uses Mail For sending email notifications via SES
     * @uses Log For error logging
     */
    public function processExports(): void
    {
        $connection = new AMQPStreamConnection(
            config('rabbitmq.host'),
            config('rabbitmq.port'),
            config('rabbitmq.user'),
            config('rabbitmq.password'),
            config('rabbitmq.vhost')
        );

        $channel = $connection->channel();
        $channel->queue_declare('catalog_export', false, true, false, false);

        $callback = function ($msg) {
            try {
                $filename = 'catalog_export_' . now()->format('Ymd_His') . '.csv';

                Storage::disk('s3')->put("exports/{$filename}", $msg->body);

                Mail::mailer('ses')
                    ->to(env('ADMIN_EMAIL'))
                    ->send(new CatalogExported(
                        $filename,
                        Storage::disk('s3')->temporaryUrl("exports/{$filename}", now()->addHour())
                    ));

                $msg->ack();
            } catch (Exception $e) {
                Log::error(__('catalog.processing_failed') . $e->getMessage());
                $msg->nack();
            }
        };

        $channel->basic_consume('catalog_export', '', false, false, false, false, $callback);

        while ($channel->is_consuming()) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }
}
