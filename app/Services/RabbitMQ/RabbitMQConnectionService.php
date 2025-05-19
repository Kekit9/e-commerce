<?php

declare(strict_types=1);

namespace App\Services\RabbitMQ;

use App\Services\RabbitMQ\Interfaces\RabbitMQConnectionServiceInterface;
use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQConnectionService implements RabbitMQConnectionServiceInterface
{
    private AMQPStreamConnection $connection;

    /**
     * @throws Exception
     */
    public function __construct(
        string $host,
        int $port,
        string $user,
        string $password,
        string $vhost
    ) {
        $this->connection = new AMQPStreamConnection($host, $port, $user, $password, $vhost);
    }

    public function getConnection(): AMQPStreamConnection
    {
        return $this->connection;
    }
}
