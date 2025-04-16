<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;
use App\Services\ServiceService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Service Management
 *
 * APIs for managing services
 */
class ServiceController extends Controller
{
    /**
     * The service instance
     *
     * @var ServiceService
     */
    protected ServiceService $serviceService;

    /**
     * ServiceController constructor
     *
     * @param ServiceService $serviceService The service instance
     */
    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }

    /**
     * Get all services
     *
     * @param Request $request
     *
     * @return JsonResponse filtered butch of items
     *
     * @throws AuthorizationException
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Service::class);

        $services = $this->serviceService->getAllServices($request);

        return response()->json($services);
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
        return $this->serviceService->createService($request->validated());
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
