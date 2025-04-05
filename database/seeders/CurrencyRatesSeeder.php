<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Seeder for initial currency rates loading
 */
class CurrencyRatesSeeder extends Seeder
{
    /**
     * Run the database seeds
     *
     * @return void
     */
    public function run()
    {
        \Artisan::call('currency-rates:update');
        $this->command->info('Initial currency rates loaded!');
    }
}
