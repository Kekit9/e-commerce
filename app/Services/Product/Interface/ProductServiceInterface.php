<?php

declare(strict_types=1);

namespace App\Services\Product\Interface;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductServiceInterface
{
    /**
     * Get all products with filtering and pagination
     *
     * @param array<string, mixed> $params Filtering and sorting parameters
     *
     * @return array{products: LengthAwarePaginator, currency_rates: array<string, float>}
     */
    public function getAllProducts(array $params): array;

    /**
     * Create a new product
     *
     * @param array<string, mixed> $data Product data
     *
     * @return JsonResponse
     */
    public function createProduct(array $data): JsonResponse;

    /**
     * Update existing product
     *
     * @param array<string, mixed> $data Product data
     * @param int $id Product ID
     *
     * @return JsonResponse
     *
     * @throws ModelNotFoundException
     */
    public function updateProduct(array $data, int $id): JsonResponse;

    /**
     * Delete product
     *
     * @param int $id Product ID
     *
     * @return JsonResponse
     *
     * @throws ModelNotFoundException
     */
    public function deleteProduct(int $id): JsonResponse;
}
