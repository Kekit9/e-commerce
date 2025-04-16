<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\Log;

class RabbitMQService
{
    /**
     * Publish CSV content to RabbitMQ queue
     *
     * @throws Exception
     */
    public function publishToCatalogExportQueue(string $content): void
    {
        Log::info(__('rabbitmq.publishing_in_process') . strlen($content));

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

        $message = new AMQPMessage($content, [
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]);

        $channel->basic_publish($message, '', 'catalog_export');

        $channel->close();
        $connection->close();
    }
}
