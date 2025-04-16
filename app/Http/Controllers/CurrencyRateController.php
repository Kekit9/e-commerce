<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\CurrencyRateService;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class CurrencyRateController extends Controller
{
    /**
     * The CurrencyRateService service instance
     *
     * @var CurrencyRateService
     */
    private CurrencyRateService $currencyRateService;

    /**
     * @param CurrencyRateService $currencyRateService
     */
    public function __construct(CurrencyRateService $currencyRateService)
    {
        $this->currencyRateService = $currencyRateService;
    }

    /**
     * Update currency rates manually
     *
     * @return array
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
