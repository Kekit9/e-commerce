<?php

namespace App\Providers;

use App\Interfaces\CatalogExportRepositoryInterface;
use App\Interfaces\CurrencyRateRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use App\Interfaces\ServiceRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\CatalogExportRepository;
use App\Repositories\CurrencyRateRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\UserRepository;
use App\Services\CurrencyRateService;
use App\Services\SystemClock;
use Illuminate\Support\ServiceProvider;
use Psr\Clock\ClockInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ServiceRepositoryInterface::class, ServiceRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(CurrencyRateRepositoryInterface::class, CurrencyRateRepository::class);
        $this->app->bind(CatalogExportRepositoryInterface::class, CatalogExportRepository::class);
        $this->app->when(CurrencyRateService::class)
            ->needs('$currencyUrl')
            ->giveConfig('services.currency.url');
        $this->app->bind(ClockInterface::class, SystemClock::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
