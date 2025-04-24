<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\CatalogImportService;
use Exception;
use Illuminate\Console\Command;

class ConsumeCatalogExports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catalog:consume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process catalog exports from RabbitMQ queue';

    /**
     * Execute the console command.
     *
     * @param CatalogImportService $importService
     *
     * @throws Exception
     */
    public function handle(CatalogImportService $importService): void
    {
        $this->info(__('catalog.consuming_started'));
        $importService->processExports();
    }
}
