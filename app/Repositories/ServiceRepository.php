<?php

namespace App\Repositories;

use App\Interfaces\ServiceRepositoryInterface;
use App\Models\Service;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

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
     * @return array Returns a collection of all services
     */
    public function getAllServices(Request $request): array
    {
        $sortBy = $request->query('sort_by', self::DEFAULT_SORT_VALUE);
        $sortDirection = $request->query('sort_direction', self::DEFAULT_SORT_DIRECTION);
        $perPage = $request->query('per_page', self::DEFAULT_PER_PAGE);
        $serviceType = $request->query('service_type');

        $query = $this->model->query();

        if ($serviceType !== null) {
            $query->where('service_type', $serviceType);
        }

        $query->orderBy($sortBy, $sortDirection);

        return [
            'services' => $query->paginate($perPage),
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
