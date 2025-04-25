<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

class RabbitMQService
{
    /**
     * RabbitMQService constructor.
     *
     * @param LoggerInterface $logger
     * @param AMQPStreamConnection $connection
     */
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly AMQPStreamConnection $connection
    ) {
    }

    /**
     * Publish CSV content to RabbitMQ queue
     *
     * @param string $content
     *
     * @return void
     *
     * @throws Exception
     */
    public function publishToCatalogExportQueue(string $content): void
    {
        $this->logger->info(__('rabbitmq.publishing_in_process'), [
            'content_length' => strlen($content),
            'queue' => 'catalog_export'
        ]);

        $channel = $this->connection->channel();

        $channel->queue_declare(
            queue: 'catalog_export',
            passive: false,
            durable: true,
            exclusive: false,
            auto_delete: false
        );

        $message = new AMQPMessage($content, [
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]);

        $channel->basic_publish($message, '', 'catalog_export');

        $channel->close();
    }
}
