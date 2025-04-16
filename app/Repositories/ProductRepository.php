<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ProductRepository implements ProductRepositoryInterface
{
    public const DEFAULT_PER_PAGE = 10;

    public const DEFAULT_SORT_VALUE = 'id';

    public const DEFAULT_SORT_DIRECTION = 'asc';

    /**
     * @var Product The product model instance
     */
    protected Product $model;


    private CurrencyRateRepository $currencyRateRepository;

    /**
     * ProductRepository constructor
     *
     * @param Product $product The product model
     */
    public function __construct(Product $product, CurrencyRateRepository $currencyRateRepository)
    {
        $this->model = $product;
        $this->currencyRateRepository = $currencyRateRepository;
    }

    /**
     * Get all products with relationships
     *
     * @param Request $request
     *
     * @return array
     */
    public function getAllProducts(Request $request): array
    {
        $makerId = $request->query('maker_id');
        $serviceId = $request->query('service_id');
        $sortBy = $request->query('sort_by', self::DEFAULT_SORT_VALUE);
        $sortDirection = $request->query('sort_direction', self::DEFAULT_SORT_DIRECTION);
        $perPage = $request->query('per_page', self::DEFAULT_PER_PAGE);

        $query = $this->model->with('maker', 'services');

        if ($makerId !== null) {
            $query->where('maker_id', $makerId);
        }

        if ($serviceId !== null) {
            $query->whereIn('service_id', $serviceId);
        }

        $query->orderBy($sortBy, $sortDirection);

        return [
            'products' => $query->paginate($perPage),
            'currency_rates' => $this->currencyRateRepository->getAllRates()
        ];
    }

    /**
     * Create a new product
     *
     * @param array<string, mixed> $data Product data
     *
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
     *
     * @return Product The updated product with maker relationship
     *
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
     *
     * @return bool True if deletion was successful
     *
     * @throws ModelNotFoundException
     */
    public function deleteProduct(int $id): bool
    {
        $product = $this->model->findOrFail($id);
        $product->delete();

        return true;
    }
}
