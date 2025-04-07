<?php

namespace App\Repositories;

use App\Interfaces\CurrencyRateRepositoryInterface;
use App\Models\CurrencyRate;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;
use SimpleXMLElement;
use Carbon\Carbon;

class CurrencyRateRepository implements CurrencyRateRepositoryInterface
{
    /**
     * @throws Exception
     *
     * Currency rates repository implementation
     */
    public function fetchAndUpdateRates(): array
    {
        try {
            $response = Http::withOptions(['verify' => false])
                ->get('https://bankdabrabyt.by/export_courses.php');

            if (!$response->successful()) {
                return ['success' => false, 'message' => 'HTTP request failed'];
            }

            $xml = new SimpleXMLElement($response->body());
            $centralOffice = $xml->filials->filial[0];
            $now = Carbon::now();

            $ratesToStore = [];
            foreach ($centralOffice->rates->value as $value) {
                $iso = (string)$value['iso'];
                if (in_array($iso, ['USD', 'EUR', 'RUB'])) {
                    $ratesToStore[] = [
                        'currency_iso' => $iso,
                        'currency_code' => (string)$value['code'],
                        'buy_rate' => (float)$value['buy'],
                        'sale_rate' => (float)$value['sale'],
                        'last_updated' => $now
                    ];
                }
            }

            $this->updateOrCreateRates($ratesToStore);
            return ['success' => true, 'message' => 'Rates updated'];

        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Update or create currency rates in database
     *
     * @param array $rates Array of rate data
     * @return void
     */
    private function updateOrCreateRates(array $rates): void
    {
        foreach ($rates as $rate) {
            CurrencyRate::query()->updateOrCreate(
                ['currency_iso' => $rate['currency_iso']],
                $rate
            );
        }
    }

    /**
     * Get all existing currency rates
     *
     * @return Collection
     */
    public function getAllRates(): Collection
    {
        return CurrencyRate::all();
    }
}
