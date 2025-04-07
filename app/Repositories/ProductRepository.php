<?php

namespace App\Repositories;

use App\Interfaces\CurrencyRateRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductRepository implements ProductRepositoryInterface
{
    /**
     * @var Product The product model instance
     */
    protected Product $model;

    /**
     * ProductRepository constructor
     *
     * @param Product $product The product model
     */
    public function __construct(Product $product)
    {
        $this->model = $product;
    }

    /**
     * Get all products with relationships
     *
     * @param array $filters
     * @param string $sortBy
     * @param string $sortDirection
     * @param int $perPage
     * @return array
     * @return array Returns array of products with makers and services
     */
    public function getAllProducts(array $filters = [], string $sortBy = 'id', string $sortDirection = 'asc', int $perPage = 10): array
    {
        $query = $this->model->with('maker', 'services');

        if (!empty($filters['maker_id'])) {
            $query->where('maker_id', $filters['maker_id']);
        }

        if (!empty($filters['service_id'])) {
            $query->whereHas('services', function($q) use ($filters) {
                $q->where('services.id', $filters['service_id']);
            });
        }

        $query->orderBy($sortBy, $sortDirection);

        return [
            'products' => $query->paginate($perPage),
            'currency_rates' => app(CurrencyRateRepositoryInterface::class)->getAllRates()
        ];
    }

    /**
     * Create a new product
     *
     * @param array<string, mixed> $data Product data
     * @return Product The created product
     */
    public function createProduct(array $data): Product
    {
        return $this->model->create($data);
    }

    /**
     * Update an existing product
     *
     * @param array<string, mixed> $data Product data
     * @param int $id Product ID
     * @return Product The updated product with maker relationship
     * @throws ModelNotFoundException
     */
    public function updateProduct(array $data, int $id): Product
    {
        $product = $this->model->findOrFail($id);
        $product->update($data);
        return $product->refresh()->load('maker');
    }

    /**
     * Delete a product
     *
     * @param int $id Product ID
     * @return bool True if deletion was successful
     * @throws ModelNotFoundException
     */
    public function deleteProduct(int $id): bool
    {
        $product = $this->model->findOrFail($id);
        $product->delete();
        return true;
    }
}
