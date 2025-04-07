<?php

namespace App\Services;

use App\Mail\CatalogExported;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class CatalogImportService
{
    /**
     * @throws Exception
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
                Log::error('Catalog processing failed: ' . $e->getMessage());
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
