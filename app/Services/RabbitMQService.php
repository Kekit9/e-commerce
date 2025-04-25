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
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * RabbitMQService constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
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

        $connection = new AMQPStreamConnection(
            config('rabbitmq.host'), // todo: такие штучки надо кидать в конструктор, а вообще в идеале готовый инстанс коннекшна получать в констурктор
            config('rabbitmq.port'),
            config('rabbitmq.user'),
            config('rabbitmq.password'),
            config('rabbitmq.vhost')
        );

        $channel = $connection->channel();

        $channel->queue_declare(
            'catalog_export',
            false, // todo: named parameters, сейчас сложно читать
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
