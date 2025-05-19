<?php

declare(strict_types=1);

namespace App\Services\Service;

use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Repositories\Service\Interface\ServiceRepositoryInterface;
use App\Repositories\Service\ServiceRepository;
use App\Services\Service\Interface\ServiceServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class ServiceService implements ServiceServiceInterface
{
    /**
     * ServiceService constructor
     *
     * @param ServiceRepositoryInterface $serviceRepository The service repository
     */
    public function __construct(
        protected ServiceRepositoryInterface $serviceRepository
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
     * @return array<string, mixed>
     */
    public function getAllServices(array $params): array
    {
        return $this->serviceRepository->getAllServices([
            'sort_by' => $params['sort_by'] ?? ServiceRepository::DEFAULT_SORT_VALUE,
            'sort_direction' => $params['sort_direction'] ?? ServiceRepository::DEFAULT_SORT_DIRECTION,
            'per_page' => $params['per_page'] ?? ServiceRepository::DEFAULT_PER_PAGE,
            'service_type' => $params['service_type'] ?? null,
        ]);
    }

    /**
     * Create a new service
     *
     * @param array<string, mixed> $data The service data to create
     *
     * @return Service Returns JSON response with the created service resource
     */
    public function createService(array $data): Service
    {
        return $this->serviceRepository->createService($data);
    }

    /**
     * Update an existing service
     *
     * @param array<string, mixed> $data The service data to update
     * @param int $id The ID of the service to update
     *
     * @return JsonResponse Returns JSON response with the updated service resource
     *
     * @throws ModelNotFoundException
     */
    public function updateService(array $data, int $id): JsonResponse
    {
        $service = $this->serviceRepository->updateService($data, $id);

        return response()->json(new ServiceResource($service));
    }

    /**
     * Delete a service
     *
     * @param int $id The ID of the service to delete
     *
     * @return JsonResponse Returns JSON response with success message
     *
     * @throws ModelNotFoundException
     */
    public function deleteService(int $id): JsonResponse
    {
        $this->serviceRepository->deleteService($id);

        return response()->json();
    }
}
