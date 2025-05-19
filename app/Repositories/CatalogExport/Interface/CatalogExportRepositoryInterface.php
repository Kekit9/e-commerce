<?php

namespace App\Repositories\CatalogExport\Interface;

use Illuminate\Database\Eloquent\Collection;

interface CatalogExportRepositoryInterface
{
    /**
     * Export product catalog to CSV and send to S3
     *
     * @return Collection
     */
    public function getProductsForExport(): Collection;
}
