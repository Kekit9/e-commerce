<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

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
     * @return Collection<int, Product> Returns collection of products with makers and services
     */
    public function getAllProducts(): Collection
    {
        return $this->model->with('maker', 'services')->get();
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
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
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
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function deleteProduct(int $id): bool
    {
        $product = $this->model->findOrFail($id);
        $product->delete();
        return true;
    }

    /**
     * Find a product by ID
     *
     * @param int $id Product ID
     * @return Product The found product
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findProduct(int $id): Product
    {
        return $this->model->findOrFail($id);
    }
}
