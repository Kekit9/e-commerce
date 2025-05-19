<?php

namespace App\Providers;

use App\Repositories\CatalogExport\CatalogExportRepository;
use App\Repositories\CatalogExport\Interface\CatalogExportRepositoryInterface;
use App\Repositories\CurrencyRate\CurrencyRateRepository;
use App\Repositories\CurrencyRate\Interface\CurrencyRateRepositoryInterface;
use App\Repositories\Product\Interface\ProductRepositoryInterface;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Service\Interface\ServiceRepositoryInterface;
use App\Repositories\Service\ServiceRepository;
use App\Repositories\User\Interface\UserRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Services\Authorization\AuthorizationService;
use App\Services\Authorization\Interface\AuthorizationServiceInterface;
use App\Services\Clock\SystemClock;
use App\Services\Product\Interface\ProductServiceInterface;
use App\Services\Product\ProductService;
use App\Services\RabbitMQ\CatalogExport\CatalogExportService;
use App\Services\RabbitMQ\CatalogExport\Interface\CatalogExportServiceInterface;
use App\Services\RabbitMQ\CatalogImport\CatalogImportService;
use App\Services\RabbitMQ\CatalogImport\Interface\CatalogImportServiceInterface;
use App\Services\RabbitMQ\CurrencyRate\CurrencyRateService;
use App\Services\RabbitMQ\CurrencyRate\Interface\CurrencyRateServiceInterface;
use App\Services\RabbitMQ\Interfaces\RabbitMQConnectionServiceInterface;
use App\Services\RabbitMQ\Interfaces\RabbitMQServiceInterface;
use App\Services\RabbitMQ\RabbitMQConnectionService;
use App\Services\RabbitMQ\RabbitMQService;
use App\Services\Registration\Interface\RegistrationServiceInterface;
use App\Services\Registration\RegistrationService;
use App\Services\Service\Interface\ServiceServiceInterface;
use App\Services\Service\ServiceService;
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
        $this->app->bind(AuthorizationServiceInterface::class, AuthorizationService::class);
        $this->app->bind(RegistrationServiceInterface::class, RegistrationService::class);
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
        $this->app->bind(ServiceServiceInterface::class, ServiceService::class);
        $this->app->bind(CatalogExportServiceInterface::class, CatalogExportService::class);
        $this->app->bind(RabbitMQServiceInterface::class, RabbitMQService::class);
        $this->app->bind(CatalogImportServiceInterface::class, CatalogImportService::class);
        $this->app->bind(
            RabbitMQConnectionServiceInterface::class,
            function () {
                return new RabbitMQConnectionService(
                    config('rabbitmq.host'),
                    config('rabbitmq.port'),
                    config('rabbitmq.user'),
                    config('rabbitmq.password'),
                    config('rabbitmq.vhost')
                );
            }
        );
        $this->app->bind(CurrencyRateServiceInterface::class, CurrencyRateService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
