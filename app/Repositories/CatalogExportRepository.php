<?php

declare(strict_types=1);

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
     * todo: это может быть на уровне репы продуктов
     */
    public function getProductsForExport(): Collection
    {
        return Product::with(['maker', 'services'])->get();
    }
}
