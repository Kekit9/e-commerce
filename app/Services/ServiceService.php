<?php

namespace App\Services;

use App\Interfaces\ServiceRepositoryInterface;
use App\Http\Resources\ServiceResource;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

class ServiceService
{
    /**
     * The service repository instance
     *
     * @var ServiceRepositoryInterface
     */
    protected ServiceRepositoryInterface $serviceRepository;

    /**
     * ServiceService constructor
     *
     * @param ServiceRepositoryInterface $serviceRepository The service repository
     */
    public function __construct(ServiceRepositoryInterface $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    /**;
     * @param array $filters
     * @param string $sortBy
     * @param string $sortDirection
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllServices(array $filters = [], string $sortBy = 'id', string $sortDirection = 'asc', int $perPage = 10): LengthAwarePaginator
    {
        return $this->serviceRepository->getAllServices(
            $filters,
            $sortBy,
            $sortDirection,
            $perPage
        );
    }

    /**
     * Create a new service
     *
     * @param array<string, mixed> $data The service data to create
     * @return JsonResponse Returns JSON response with the created service resource
     */
    public function createService(array $data): JsonResponse
    {
        $service = $this->serviceRepository->create($data);
        return response()->json(new ServiceResource($service), 201);
    }

    /**
     * Update an existing service
     *
     * @param array<string, mixed> $data The service data to update
     * @param int $id The ID of the service to update
     * @return JsonResponse Returns JSON response with the updated service resource
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function updateService(array $data, int $id): JsonResponse
    {
        $service = $this->serviceRepository->update($data, $id);
        return response()->json(new ServiceResource($service), 200);
    }

    /**
     * Delete a service
     *
     * @param int $id The ID of the service to delete
     * @return JsonResponse Returns JSON response with success message
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function deleteService(int $id): JsonResponse
    {
        $this->serviceRepository->delete($id);
        return response()->json(['message' => 'Service deleted successfully'], 204);
    }
}
