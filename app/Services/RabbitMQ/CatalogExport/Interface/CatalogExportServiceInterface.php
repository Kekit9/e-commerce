<?php

declare(strict_types=1);

namespace App\Services\RabbitMQ\CatalogExport\Interface;

use Illuminate\Database\Eloquent\Collection;
use League\Csv\CannotInsertRecord;
use League\Csv\Exception;

interface CatalogExportServiceInterface
{
    /**
     * Export catalog to CSV and publish to RabbitMQ
     *
     * @return array{
     *     success: bool,
     *     message: string,
     *     error: string|null
     * }
     *
     * @throws Exception
     */
    public function exportCatalog(): array;

    /**
     * Generate CSV content from products collection
     *
     * @param Collection $products
     *
     * @return string
     *
     * @throws CannotInsertRecord
     * @throws Exception
     */
    public function generateCsvContent(Collection $products): string;
}
