<?php

declare(strict_types=1);

namespace App\Repositories\CatalogExport;

use App\Models\Product;
use App\Repositories\CatalogExport\Interface\CatalogExportRepositoryInterface;
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
