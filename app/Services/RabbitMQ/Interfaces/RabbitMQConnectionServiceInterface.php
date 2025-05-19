<?php

declare(strict_types=1);

namespace App\Services\RabbitMQ\Interfaces;

use PhpAmqpLib\Connection\AMQPStreamConnection;

interface RabbitMQConnectionServiceInterface
{
    /**
     * Creates and returns RabbitMQ channel
     *
     * @return AMQPStreamConnection RabbitMQ channel instance
     */
    public function getConnection(): AMQPStreamConnection;
}
