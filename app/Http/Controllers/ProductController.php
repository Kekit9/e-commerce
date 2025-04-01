<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * @group Product Management
 *
 * APIs for managing products
 */
class ProductController extends Controller
{
    /**
     * The product service instance
     *
     * @var ProductService
     */
    protected ProductService $productService;

    /**
     * ProductController constructor
     *
     * @param ProductService $productService The product service
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Get all products
     *
     * @return Collection<int, Product> Returns collection of products
     */
    public function index(): Collection
    {
        return $this->productService->getAllProducts();
    }

    /**
     * Create a new product
     *
     * @param CreateProductRequest $request The validated request
     * @return JsonResponse Returns created product
     */
    public function create(CreateProductRequest $request): JsonResponse
    {
        return $this->productService->createProduct($request->validated());
    }

    /**
     * Update a product
     *
     *
     * @param UpdateProductRequest $request The validated request
     * @param int $id Product ID
     * @return JsonResponse Returns updated product
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        return $this->productService->updateProduct($request->validated(), $id);
    }

    /**
     * Delete a product
     *
     * @param int $id Product ID
     * @return JsonResponse Returns success message
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function delete(int $id): JsonResponse
    {
        return $this->productService->deleteProduct($id);
    }
}
