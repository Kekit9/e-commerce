<?php

declare(strict_types=1);

namespace App\Services\Product;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Repositories\Product\Interface\ProductRepositoryInterface;
use App\Services\Product\Interface\ProductServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService implements ProductServiceInterface
{
    /**
     * ProductService constructor
     *
     * @param ProductRepositoryInterface $productRepository The product repository
     */
    public function __construct(
        protected ProductRepositoryInterface $productRepository
    ) {
    }

    /**
     * Get all products
     *
     * @param array{
     *     maker_id: int|null,
     *     service_id: int|null,
     *     sort_by: string,
     *     sort_direction: 'asc'|'desc',
     *     per_page: int
     * } $params
     *
     * @return array{
     *     products: LengthAwarePaginator<Product>,
     *     currency_rates: array<string, mixed>
     * }
     */
    public function getAllProducts(array $params): array
    {
        return $this->productRepository->getAllProducts($params);
    }

    /**
     * Create a new product
     *
     * @param array<string, mixed> $data Product data
     *
     * @return JsonResponse Returns JSON response with created product
     */
    public function createProduct(array $data): JsonResponse
    {
        $product = $this->productRepository->createProduct($data);

        return response()->json(new ProductResource($product));
    }

    /**
     * Update an existing product
     *
     * @param array<string, mixed> $data Product data
     * @param int $id Product ID
     *
     * @return JsonResponse Returns JSON response with updated product
     *
     * @throws ModelNotFoundException
     */
    public function updateProduct(array $data, int $id): JsonResponse
    {
        $product = $this->productRepository->updateProduct($data, $id);

        return response()->json([
            'success' => true,
            'data' => new ProductResource($product),
        ]);
    }

    /**
     * Delete a product
     *
     * @param int $id Product ID
     *
     * @return JsonResponse Returns JSON response with success message
     *
     * @throws ModelNotFoundException
     */
    public function deleteProduct(int $id): JsonResponse
    {
        $this->productRepository->deleteProduct($id);

        return response()->json([
            'message' => __('product.deleted_successfully'),
            'id' => $id
        ]);
    }
}
