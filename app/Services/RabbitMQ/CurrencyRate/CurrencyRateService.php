<?php

declare(strict_types=1);

namespace App\Services\RabbitMQ\CurrencyRate;

use App\Repositories\CurrencyRate\Interface\CurrencyRateRepositoryInterface;
use App\Services\RabbitMQ\CurrencyRate\Interface\CurrencyRateServiceInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Client\Factory as HttpClientFactory;
use Psr\Clock\ClockInterface;
use SimpleXMLElement;

class CurrencyRateService implements CurrencyRateServiceInterface
{
    /**
     * Supported currency ISO codes
     */
    private const SUPPORTED_CURRENCIES = ['USD', 'EUR', 'RUB'];

    /**
     * @param CurrencyRateRepositoryInterface $currencyRateRepository
     * @param HttpClientFactory $http
     * @param ClockInterface $clock
     * @param string $currencyUrl
     */
    public function __construct(
        protected CurrencyRateRepositoryInterface $currencyRateRepository,
        protected HttpClientFactory $http,
        protected ClockInterface $clock,
        protected string $currencyUrl
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function fetchAndUpdateRates(): array
    {
        try {
            $rates = $this->fetchCurrencyRates();
            $this->currencyRateRepository->upsertRates($rates);

            return ['rates' => $rates];
        } catch (Exception $e) {
            throw new Exception(__('currency.failed_update') . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
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
            ->get($this->currencyUrl);

        if (!$response->successful()) {
            throw new Exception(__('currency.request_failed'));
        }

        $xml = new SimpleXMLElement($response->body());
        $centralOffice = $xml->filials->filial[0];
        $now = $this->clock->now();

        $rates = [];
        foreach ($centralOffice->rates->value as $value) {
            $iso = (string)$value['iso'];
            if (in_array($iso, self::SUPPORTED_CURRENCIES)) {
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
