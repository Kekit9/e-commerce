<?php

namespace App\Interfaces;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    /**
     * Get all products with relationships
     *
     * @return array Returns array of products with makers and services
     * @return LengthAwarePaginator the raw pagination object
     */
    public function getAllProducts(): LengthAwarePaginator;
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
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function updateProduct(array $data, int $id): Product;

    /**
     * Delete a product
     *
     * @param int $id Product ID
     * @return bool True if deletion was successful
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function deleteProduct(int $id): bool;

    /**
     * Find a product by ID
     *
     * @param int $id Product ID
     * @return Product The found product
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findProduct(int $id): Product;
}
