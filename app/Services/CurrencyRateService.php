<?php

namespace App\Services;

use App\Interfaces\CurrencyRateRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class CurrencyRateService
{
    /**
     * Currency rate repository instance
     *
     * @var CurrencyRateRepositoryInterface
     */
    protected CurrencyRateRepositoryInterface $currencyRateRepository;

    /**
     * @param CurrencyRateRepositoryInterface $currencyRateRepository
     */
    public function __construct(CurrencyRateRepositoryInterface $currencyRateRepository)
    {
        $this->currencyRateRepository = $currencyRateRepository;
    }

    /**
     * @throws Exception
     *
     * Update currency rates
     *
     * @return array
     */
    public function fetchAndUpdateRates(): array
    {
        return $this->currencyRateRepository->fetchAndUpdateRates();
    }

    /**
     * Get all currency rates
     *
     * @return Collection
     */
    public function getAllRates(): Collection
    {
        return $this->currencyRateRepository->getAllRates();
    }
}
