<?php

namespace App\Interfaces;

use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;

interface ServiceRepositoryInterface
{
    /**
     * Get all services
     *
     * @return Collection<int, Service> Returns a collection of all services
     */
    public function all(): Collection;

    /**
     * Create a new service record
     *
     * @param array<string, mixed> $data Service data to create
     * @return Service Returns the newly created service instance
     */
    public function create(array $data): Service;

    /**
     * Update an existing service record
     *
     * @param array<string, mixed> $data Data to update
     * @param int $id ID of the service to update
     * @return Service Returns the updated service instance
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(array $data, int $id): Service;

    /**
     * Delete a service record
     *
     * @param int $id ID of the service to delete
     * @return bool Returns true if deletion was successful
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function delete(int $id): bool;

    /**
     * Find a specific service by ID
     *
     * @param int $id ID of the service to find
     * @return Service Returns the found service instance
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function find(int $id): Service;
}
