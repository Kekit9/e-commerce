<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\CurrencyRateRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;
use Illuminate\Http\Client\Factory as HttpClientFactory;
use SimpleXMLElement;

class CurrencyRateService
{
    /**
     * Default URL for fetching currency rates
     *
     * @var string
     */
    public const DEFAULT_CURRENCY_URL = 'https://bankdabrabyt.by/export_courses.php'; // todo: ссылка должна приходить в конструктор, ты создал явную зависимость от этого доменного имени, поменяется у ребят хостинг и все будем сидеть плакать

    /**
     * Currency rate repository instance
     *
     * @var CurrencyRateRepositoryInterface
     */
    protected CurrencyRateRepositoryInterface $currencyRateRepository;

    /**
     * HTTP client factory instance
     *
     * @var HttpClientFactory
     */
    protected HttpClientFactory $http;

    /**
     * @param CurrencyRateRepositoryInterface $currencyRateRepository
     * @param HttpClientFactory $http
     */
    public function __construct(CurrencyRateRepositoryInterface $currencyRateRepository, HttpClientFactory $http)
    {
        $this->currencyRateRepository = $currencyRateRepository;
        $this->http = $http;
    }

    /**
     * Fetch rates from API and update database
     *
     * @return array<string, mixed>
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
            ]; // todo: тут можно было бы просто возвращать рэйты, без сообщений и пометки об успехе
        } catch (Exception $e) {

            return [
                'success' => false,
                'message' => $e->getMessage() // todo:  а тут кидать красивый кастомный экспешн
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
     * @return array<int, mixed>
     *
     * @throws Exception
     */
    private function fetchCurrencyRates(): array
    {
        $response = $this->http->withOptions(['verify' => false])
            ->get(self::DEFAULT_CURRENCY_URL);

        if (!$response->successful()) {
            throw new Exception(__('currency.request_failed'));
        }

        $xml = new SimpleXMLElement($response->body());
        $centralOffice = $xml->filials->filial[0];
        $now = Carbon::now(); // todo: PSR-20

        $rates = [];
        foreach ($centralOffice->rates->value as $value) {
            $iso = (string)$value['iso'];
            if (in_array($iso, ['USD', 'EUR', 'RUB'])) { // todo: коды в константы
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
