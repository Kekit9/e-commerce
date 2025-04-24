<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\ServiceRepositoryInterface;
use App\Http\Resources\ServiceResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    /**
     * Get all services
     *
     * @param Request $request
     *
     * @return array<string, mixed>
     */
    public function getAllServices(Request $request): array
    {
        return $this->serviceRepository->getAllServices($request);
    }

    /**
     * Create a new service
     *
     * @param array<string, mixed> $data The service data to create
     *
     * @return JsonResponse Returns JSON response with the created service resource
     */
    public function createService(array $data): JsonResponse
    {
        $service = $this->serviceRepository->createService($data);

        return response()->json(new ServiceResource($service));
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
