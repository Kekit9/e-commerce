<?php

declare(strict_types=1);

namespace App\Services\RabbitMQ\CatalogImport\Interface;

interface CatalogImportServiceInterface
{
    /**
     * Processes catalog exports from RabbitMQ queue
     *
     * @return void
     */
    public function processExports(): void;
}
