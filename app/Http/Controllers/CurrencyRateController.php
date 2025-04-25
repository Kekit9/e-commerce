<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\CurrencyRateService;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class CurrencyRateController extends Controller
{
    public function __construct(
        private readonly CurrencyRateService $currencyRateService
    ) {
    }

    /**
     * Update currency rates manually
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    public function updateRates(): array
    {
        return $this->currencyRateService->fetchAndUpdateRates();
    }

    /**
     * Get all currency rates
     */
    public function index(): Collection
    {
        return $this->currencyRateService->getAllRates();
    }
}
