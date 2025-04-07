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
use Illuminate\Support\ServiceProvider;

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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
