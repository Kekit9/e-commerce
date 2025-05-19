<?php

declare(strict_types=1);

namespace App\Services\Service\Interface;

use App\Models\Service;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

interface ServiceServiceInterface
{
    /**
     * Get all services with filtering and pagination
     *
     * @param array<string, mixed> $params Filtering and sorting parameters
     *
     * @return array<string, mixed>
     */
    public function getAllServices(array $params): array;

    /**
     * Create a new service
     *
     * @param array<string, mixed> $data Service data
     *
     * @return Service Created service
     */
    public function createService(array $data): Service;

    /**
     * Update existing service
     *
     * @param array<string, mixed> $data Service data
     * @param int $id Service ID
     *
     * @return JsonResponse
     *
     * @throws ModelNotFoundException
     */
    public function updateService(array $data, int $id): JsonResponse;

    /**
     * Delete service
     *
     * @param int $id Service ID
     *
     * @return JsonResponse
     *
     * @throws ModelNotFoundException
     */
    public function deleteService(int $id): JsonResponse;
}
