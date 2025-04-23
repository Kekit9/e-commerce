<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\ServiceRepositoryInterface;
use App\Models\Service;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ServiceRepository implements ServiceRepositoryInterface
{
    public const DEFAULT_PER_PAGE = 10;

    public const DEFAULT_SORT_VALUE = 'id';

    public const DEFAULT_SORT_DIRECTION = 'asc';

    /**
     * @var Service The service model instance
     */
    protected Service $model;

    /**
     * ServiceRepository constructor
     *
     * @param Service $service The service model
     */
    public function __construct(Service $service)
    {
        $this->model = $service;
    }

    /**
     * Get all services
     *
     * @param Request $request
     *
     * @return array{
     *      services: LengthAwarePaginator<Service>
     * }
     */
    public function getAllServices(Request $request): array
    {
        $sortBy = $this->getStringParam($request, 'sort_by', self::DEFAULT_SORT_VALUE);
        $sortDirection = $this->getStringParam($request, 'sort_direction', self::DEFAULT_SORT_DIRECTION);
        $perPage = $this->getIntParam($request, 'per_page', self::DEFAULT_PER_PAGE);
        $serviceType = $request->query('service_type');

        $query = $this->model->query();

        if ($serviceType !== null) {
            $query->where('service_type', $serviceType);
        }

        $query->orderBy($sortBy, $sortDirection);

        /** @var LengthAwarePaginator<Service> $paginator */
        $paginator = $query->paginate($perPage);

        return [
            'services' => $paginator,
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

    /**
     * Get string parameter from request with type safety
     */
    private function getStringParam(Request $request, string $key, string $default): string
    {
        $value = $request->query($key, $default);
        return is_array($value) ? $default : (string)$value;
    }

    /**
     * Get integer parameter from request with type safety
     */
    private function getIntParam(Request $request, string $key, int $default): int
    {
        $value = $request->query($key, (string)$default);
        return is_array($value) ? $default : (int)$value;
    }
}
