<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface CurrencyRateRepositoryInterface
{
    /**
     * Update or insert currency rates
     *
     * @param array $rates
     *
     * @return void
     */
    public function upsertRates(array $rates): void;

    /**
     * Get all currency rates from database
     *
     * @return Collection
     */
    public function getAllRates(): Collection;
}
