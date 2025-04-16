<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
     * @param Request $request
     *
     * @return JsonResponse filtered butch of items
     *
     * @throws AuthorizationException
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Product::class);

        $products = $this->productService->getAllProducts($request);

        return response()->json($products);
    }

    /**
     * Create a new product
     *
     * @param CreateProductRequest $request The validated request
     *
     * @return JsonResponse Returns created product
     */
    public function store(CreateProductRequest $request): JsonResponse
    {
        return $this->productService->createProduct($request->validated());
    }

    /**
     * Update a product
     *
     * @param UpdateProductRequest $request The validated request
     * @param int $id Product ID
     *
     * @return JsonResponse Returns updated product
     *
     * @throws ModelNotFoundException
     */
    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        return $this->productService->updateProduct($request->validated(), $id);
    }

    /**
     * Delete a product
     *
     * @param int $id Product ID
     *
     * @return JsonResponse Returns success message
     *
     * @throws ModelNotFoundException
     */
    public function destroy(int $id): JsonResponse
    {
        return $this->productService->deleteProduct($id);
    }
}
