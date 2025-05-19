<?php

declare(strict_types=1);

namespace App\Services\RabbitMQ\CurrencyRate\Interface;

use Exception;
use Illuminate\Database\Eloquent\Collection;

interface CurrencyRateServiceInterface
{
    /**
     * Fetches rates from API and updates database
     *
     * @return array<string, mixed> Result with updated rates
     *
     * @throws Exception When update fails
     */
    public function fetchAndUpdateRates(): array;

    /**
     * Retrieves all currency rates from database
     *
     * @return Collection Collection of currency rates
     */
    public function getAllRates(): Collection;
}
