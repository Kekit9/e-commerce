<?php

namespace App\Interfaces;

use App\Models\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

interface ServiceRepositoryInterface
{
    /**
     * Get all services with relationships
     *
     * @return LengthAwarePaginator the raw pagination object
     */
    public function getAllServices(): LengthAwarePaginator;

    /**
     * Create a new service record
     *
     * @param array<string, mixed> $data Service data to create
     * @return Service Returns the newly created service instance
     */
    public function createService(array $data): Service;

    /**
     * Update an existing service record
     *
     * @param array<string, mixed> $data Data to update
     * @param int $id ID of the service to update
     * @return Service Returns the updated service instance
     * @throws ModelNotFoundException
     */
    public function updateService(array $data, int $id): Service;

    /**
     * Delete a service record
     *
     * @param int $id ID of the service to delete
     * @return bool Returns true if deletion was successful
     * @throws ModelNotFoundException
     */
    public function deleteService(int $id): bool;
}
