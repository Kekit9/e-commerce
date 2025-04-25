<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    public const DEFAULT_PER_PAGE = 10;

    public const DEFAULT_SORT_VALUE = 'id';

    public const DEFAULT_SORT_DIRECTION = 'asc';

    /**
     * ProductRepository constructor
     *
     * @param Product $model
     * @param CurrencyRateRepository $currencyRateRepository
     */
    public function __construct(
        protected Product $model,
        private readonly CurrencyRateRepository $currencyRateRepository
    ) {
    }

    /**
     * Get all products with relationships
     *
     * @param array{
     *     maker_id: int|null,
     *     service_id: int|null,
     *     sort_by: string,
     *     sort_direction: 'asc'|'desc',
     *     per_page: int
     * } $params Parameters for filtering and pagination
     *
     * @return array{
     *     products: LengthAwarePaginator<Product>,
     *     currency_rates: array<string, mixed>
     * }
     */
    public function getAllProducts(array $params): array
    {
        $query = $this->model->with('maker', 'services');

        if ($params['maker_id'] !== null) {
            $query->where('maker_id', $params['maker_id']);
        }

        if ($params['service_id'] !== null) {
            $query->where('service_id', $params['service_id']);
        }

        return [
            'products' => $query
                ->orderBy($params['sort_by'], $params['sort_direction'])
                ->paginate($params['per_page']),
            'currency_rates' => $this->currencyRateRepository->getAllRates()->toArray()
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
