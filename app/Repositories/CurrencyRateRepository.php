<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\CurrencyRateRepositoryInterface;
use App\Models\CurrencyRate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CurrencyRateRepository implements CurrencyRateRepositoryInterface
{
    /**
     * Update or insert currency rates
     *
     * @param array<int, array{
     *      currency_iso: string,
     *      currency_code: string,
     *      buy_rate: float,
     *      sale_rate: float,
     *      last_updated: string
     * }> $rates Array of currency rates data
     *
     * @return void
     */
    public function upsertRates(array $rates): void
    {
        DB::table('currency_rates')->upsert(
            $rates,
            ['currency_iso'],
            ['currency_code', 'buy_rate', 'sale_rate', 'last_updated']
        );
    }

    /**
     * Get all existing currency rates
     *
     * @return Collection<int, CurrencyRate>
     */
    public function getAllRates(): Collection
    {
        return CurrencyRate::all();
    }
}
