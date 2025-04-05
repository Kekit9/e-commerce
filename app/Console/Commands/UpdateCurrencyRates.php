<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Interfaces\CurrencyRateRepositoryInterface;

class UpdateCurrencyRates extends Command
{
    /**
     * The name and signature of the console command
     * @var string
     */
    protected $signature = 'currency-rates:update';

    /**
     * The console command description
     * @var string
     */
    protected $description = 'Update currency rates from bankdabrabyt.by';

    /**
     * @param CurrencyRateRepositoryInterface $currencyRateRepository
     */
    public function __construct(private readonly CurrencyRateRepositoryInterface $currencyRateRepository)
    {
        parent::__construct();
    }

    /**
     * @throws \Exception
     *
     * Execute the console command
     *
     * @return int 0 on success, 1 on failure
     */
    public function handle()
    {
        $this->info('Starting currency rates update...');
        $result = $this->currencyRateRepository->fetchAndUpdateRates();

        if ($result['success']) {
            $this->info('Success: ' . $result['message']);
            $this->info(json_encode($this->currencyRateRepository->getAllRates(), JSON_PRETTY_PRINT));
            return 0;
        }

        $this->error('Error: ' . $result['message']);
        return 1;
    }
}
