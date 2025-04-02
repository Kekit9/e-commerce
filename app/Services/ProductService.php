<?php

namespace App\Services;

use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    /**
     * The product repository instance
     *
     * @var ProductRepositoryInterface
     */
    protected ProductRepositoryInterface $productRepository;

    /**
     * ProductService constructor
     *
     * @param ProductRepositoryInterface $productRepository The product repository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param array $filters
     * @param string $sortBy
     * @param string $sortDirection
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllProducts(array $filters = [], string $sortBy = 'id', string $sortDirection = 'asc', int $perPage = 10): LengthAwarePaginator
    {
        return $this->productRepository->getAllProducts(
            $filters,
            $sortBy,
            $sortDirection,
            $perPage
        );
    }

    /**
     * Create a new product
     *
     * @param array<string, mixed> $data Product data
     * @return JsonResponse Returns JSON response with created product
     */
    public function createProduct(array $data): JsonResponse
    {
        $product = $this->productRepository->createProduct($data);
        return response()->json($product, 201);
    }

    /**
     * Update an existing product
     *
     * @param array<string, mixed> $data Product data
     * @param int $id Product ID
     * @return JsonResponse Returns JSON response with updated product
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function updateProduct(array $data, int $id): JsonResponse
    {
        $product = $this->productRepository->updateProduct($data, $id);
        return response()->json($product, 200);
    }

    /**
     * Delete a product
     *
     * @param int $id Product ID
     * @return JsonResponse Returns JSON response with success message
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function deleteProduct(int $id): JsonResponse
    {
        $this->productRepository->deleteProduct($id);
        return response()->json(['message' => 'Product deleted successfully'], 204);
    }
}
