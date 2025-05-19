<?php

declare(strict_types=1);

namespace App\Services\RabbitMQ\Interfaces;

interface RabbitMQServiceInterface
{
    /**
     * Publish message to catalog export queue
     *
     * @param string $message
     *
     * @return void
     */
    public function publishToCatalogExportQueue(string $message): void;
}
