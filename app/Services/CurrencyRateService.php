<?php

namespace App\Services;

use App\Interfaces\CurrencyRateRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use SimpleXMLElement;

class CurrencyRateService
{
    public const DEFAULT_CURRENCY_URL = 'https://bankdabrabyt.by/export_courses.php';

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
     * Fetch rates from API and update database
     *
     * @return array
     */
    public function fetchAndUpdateRates(): array
    {
        try {
            $rates = $this->fetchCurrencyRates();
            $this->currencyRateRepository->upsertRates($rates);

            return [
                'success' => true,
                'message' => __('currency.updated'),
                'rates' => $rates
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Get all rates from database
     *
     * @return Collection
     */
    public function getAllRates(): Collection
    {
        return $this->currencyRateRepository->getAllRates();
    }

    /**
     * Fetch currency rates from external API
     *
     * @return array
     *
     * @throws Exception
     */
    private function fetchCurrencyRates(): array
    {
        $response = Http::withOptions(['verify' => false])
            ->get(self::DEFAULT_CURRENCY_URL);

        if (!$response->successful()) {
            throw new Exception(__('currency.request_failed'));
        }

        $xml = new SimpleXMLElement($response->body());
        $centralOffice = $xml->filials->filial[0];
        $now = Carbon::now();

        $rates = [];
        foreach ($centralOffice->rates->value as $value) {
            $iso = (string)$value['iso'];
            if (in_array($iso, ['USD', 'EUR', 'RUB'])) {
                $rates[] = [
                    'currency_iso' => $iso,
                    'currency_code' => (string)$value['code'],
                    'buy_rate' => (float)$value['buy'],
                    'sale_rate' => (float)$value['sale'],
                    'last_updated' => $now
                ];
            }
        }

        if (empty($rates)) {
            throw new Exception(__('currency.no_rates_found'));
        }

        return $rates;
    }
}
