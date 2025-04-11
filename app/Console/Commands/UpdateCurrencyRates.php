<?php

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
        $this->info('Starting currency rates update...');
        $result = $this->currencyRateService->fetchAndUpdateRates();

        if ($result['success']) {
            $this->info('Success: ' . $result['message']);
            $this->info(json_encode($this->currencyRateService->getAllRates(), JSON_PRETTY_PRINT));

            return 0;
        }

        $this->error('Error: ' . $result['message']);

        return 1;
    }
}
