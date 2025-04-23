<?php

namespace App\Interfaces;

use App\Models\CurrencyRate;
use Illuminate\Database\Eloquent\Collection;

interface CurrencyRateRepositoryInterface
{
    /**
     * Update or insert currency rates
     *
     * @param array<int, array{
     *     currency_iso: string,
     *     currency_code: string,
     *     buy_rate: float,
     *     sale_rate: float,
     *     last_updated: string
     * }> $rates Array of currency rate data
     *
     * @return void
     */
    public function upsertRates(array $rates): void;

    /**
     * Get all currency rates from database
     *
     * @return Collection<int, CurrencyRate>
     */
    public function getAllRates(): Collection;
}
