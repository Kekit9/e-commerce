<?php

namespace App\Interfaces;

use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;

interface ProductRepositoryInterface
{
    /**
     * Get all products with relationships
     *
     * @return array Returns collection of products with makers and services
     */
    public function getAllProducts(): array;

    /**
     * Create a new product
     *
     * @param array<string, mixed> $data Product data
     * @return Product The created product
     */
    public function createProduct(array $data): Product;

    /**
     * Update an existing product
     *
     * @param array<string, mixed> $data Product data
     * @param int $id Product ID
     * @return Product The updated product with maker relationship
     * @throws ModelNotFoundException
     */
    public function updateProduct(array $data, int $id): Product;

    /**
     * Delete a product
     *
     * @param int $id Product ID
     * @return bool True if deletion was successful
     * @throws ModelNotFoundException
     */
    public function deleteProduct(int $id): bool;
}
