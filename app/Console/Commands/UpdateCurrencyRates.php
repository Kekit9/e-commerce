<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\CurrencyRateService;
use Exception;
use Illuminate\Console\Command;

class UpdateCurrencyRates extends Command
{
    /**
     * The name and signature of the console command
     *
     * @var string
     */
    protected $signature = 'currency-rates:update';

    /**
     * The console command description
     *
     * @var string
     */
    protected $description = 'Update currency rates from bankdabrabyt.by';

    /**
     * @param CurrencyRateService $currencyRateService
     */
    public function __construct(private readonly CurrencyRateService $currencyRateService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command
     *
     * @throws Exception
     *
     * @return int 0 on success, 1 on failure
     */
    public function handle(): int
    {
        $this->info(__('currency.update_started'));
        $result = $this->currencyRateService->fetchAndUpdateRates();

        if ($result['success']) {
            $this->info('Success: ' . $result['message']);

            $rates = $this->currencyRateService->getAllRates();
            $json = json_encode($rates->toArray(), JSON_PRETTY_PRINT);

            if ($json === false) {
                $this->warn(__('currency.warning'));
                $this->info(__('currency.updated_but'));
            } else {
                $this->info(__('currency.updated'));
                $this->info($json);
            }

            return 0;
        }

        $this->error('Error: ' . $result['message']);

        return 1;
    }
}
