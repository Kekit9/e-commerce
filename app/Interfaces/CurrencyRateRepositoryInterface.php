<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface CurrencyRateRepositoryInterface
{
    /**
     * Fetch and update currency rates from external source
     *
     * @return array
     */
    public function fetchAndUpdateRates(): array;

    /**
     * Get all currency rates from database
     *
     * @return Collection
     */
    public function getAllRates(): Collection;
}
