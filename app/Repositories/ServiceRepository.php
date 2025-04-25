<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\ServiceRepositoryInterface;
use App\Models\Service;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

class ServiceRepository implements ServiceRepositoryInterface
{
    public const DEFAULT_PER_PAGE = 10;

    public const DEFAULT_SORT_VALUE = 'id';

    public const DEFAULT_SORT_DIRECTION = 'asc';

    /**
     * ServiceRepository constructor
     *
     * @param Service $model
     */
    public function __construct(
        protected Service $model
    ) {
    }

    /**
     * Get all services
     *
     * @param array{
     *     sort_by?: string,
     *     sort_direction?: 'asc'|'desc',
     *     per_page?: int,
     *     service_type?: string|null,
     * } $params Parameters for filtering and pagination
     *
     * @return array{
     *     services: LengthAwarePaginator<Service>
     * }
     */
    public function getAllServices(array $params): array
    {
        $sortBy = $params['sort_by'] ?? self::DEFAULT_SORT_VALUE;
        $sortDirection = $params['sort_direction'] ?? self::DEFAULT_SORT_DIRECTION;
        $perPage = $params['per_page'] ?? self::DEFAULT_PER_PAGE;
        $serviceType = $params['service_type'] ?? null;

        $query = $this->model->newQuery();

        if ($serviceType !== null) {
            $query->where('service_type', $serviceType);
        }

        $query->orderBy($sortBy, $sortDirection);

        return [
            'services' => $query->paginate($perPage)
        ];
    }

    /**
     * Create a new service record
     *
     * @param array<string, mixed> $data Service data to create
     *
     * @return Service Returns the newly created service instance
     */
    public function createService(array $data): Service
    {
        return $this->model->create($data);
    }

    /**
     * Update an existing service record
     *
     * @param array<string, mixed> $data Data to update
     * @param int $id ID of the service to update
     *
     * @return Service Returns the updated service instance
     *
     * @throws ModelNotFoundException
     */
    public function updateService(array $data, int $id): Service
    {
        $service = $this->model->findOrFail($id);
        $service->update($data);

        /** @var Service */
        return $service->fresh();
    }

    /**
     * Delete a service record
     *
     * @param int $id ID of the service to delete
     *
     * @return bool Returns true if deletion was successful
     *
     * @throws ModelNotFoundException
     */
    public function deleteService(int $id): bool
    {
        $service = $this->model->findOrFail($id);
        $service->delete();

        return true;
    }
}
