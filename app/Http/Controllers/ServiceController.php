<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceRequest;
use App\Http\Requests\ServiceListRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Services\Service\Interface\ServiceServiceInterface;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{
    /**
     * ServiceController constructor
     *
     * @param ServiceServiceInterface $serviceService The service instance
     */
    public function __construct(
        protected ServiceServiceInterface $serviceService
    ) {
    }

    /**
     * Get all services
     *
     * @param ServiceListRequest $request
     *
     * @return JsonResponse filtered butch of items
     *
     * @throws AuthorizationException
     * @throws Exception
     */
    public function index(ServiceListRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Service::class);

        return response()->json($this->serviceService->getAllServices($request->validated()));
    }

    /**
     * Create a new service
     *
     * @param CreateServiceRequest $request The validated create request
     *
     * @return JsonResponse Returns the newly created service resource
     */
    public function store(CreateServiceRequest $request): JsonResponse
    {
        $service = $this->serviceService->createService($request->validated());

        return response()->json(new ServiceResource($service));
    }

    /**
     * Update an existing service
     *
     * @param UpdateServiceRequest $request The validated update request
     * @param int $id The ID of the service to update
     *
     * @return JsonResponse Returns the updated service resource
     *
     * @throws ModelNotFoundException
     */
    public function update(UpdateServiceRequest $request, int $id): JsonResponse
    {
        return $this->serviceService->updateService($request->validated(), $id);
    }

    /**
     * Delete a service
     *
     * @urlParam id int required The ID of the service to delete. Example: 1
     *
     * @param int $id The ID of the service to delete
     *
     * @return JsonResponse Returns success message
     *
     * @throws ModelNotFoundException
     */
    public function destroy(int $id): JsonResponse
    {
        return $this->serviceService->deleteService($id);
    }
}
