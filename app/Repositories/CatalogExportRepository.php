<?php

namespace App\Repositories;

use App\Interfaces\CatalogExportRepositoryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class CatalogExportRepository implements CatalogExportRepositoryInterface
{
    /**
     * Get products with relations for export
     *
     * @return Collection
     */
    public function getProductsForExport(): Collection
    {
        return Product::with(['maker', 'services'])->get();
    }
}
