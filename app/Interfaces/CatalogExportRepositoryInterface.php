<?php

namespace App\Interfaces;

interface CatalogExportRepositoryInterface
{
    /**
     * Export product catalog to CSV and send to S3
     *
     * @return array
     *     'success' => bool,
     *     'message' => string,
     *     'file' => string|null,
     *     'url' => string|null,
     *     'error' => string|null
     *
     */
    public function exportCatalogToCsv(): array;
}
