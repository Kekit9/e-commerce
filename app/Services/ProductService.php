<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Resources\ProductResource;
use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
     * Get all products
     *
     * @param Request $request
     *
     * @return array<string, mixed>
     */
    public function getAllProducts(Request $request): array
    {
        return $this->productRepository->getAllProducts($request);
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
        return response()->json(new ProductResource($product));
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
