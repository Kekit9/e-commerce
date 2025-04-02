<?php

namespace App\Repositories;

use App\Interfaces\ServiceRepositoryInterface;
use App\Models\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ServiceRepository implements ServiceRepositoryInterface
{
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
     * @param array $filters
     * @param string $sortBy
     * @param string $sortDirection
     * @param int $perPage
     * @return LengthAwarePaginator Returns a collection of all services
     */
    public function getAllServices(array $filters = [], string $sortBy = 'id', string $sortDirection = 'asc', int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->query();

        if (!empty($filters['service_type'])) {
            $query->where('service_type', $filters['service_type']);
        }

        $query->orderBy($sortBy, $sortDirection);

        return $query->paginate($perPage);
    }

    /**
     * Create a new service record
     *
     * @param array<string, mixed> $data Service data to create
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
     * @return Service Returns the updated service instance
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
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
     * @return bool Returns true if deletion was successful
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function deleteService(int $id): bool
    {
        $service = $this->model->findOrFail($id);
        $service->delete();
        return true;
    }

    /**
     * Find a specific service by ID
     *
     * @param int $id ID of the service to find
     * @return Service Returns the found service instance
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findService(int $id): Service
    {
        return $this->model->findOrFail($id);
    }
}
