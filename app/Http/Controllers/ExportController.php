<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\CatalogExportService;
use Illuminate\Http\JsonResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ExportController extends Controller
{
    /**
     * The CatalogExport service instance
     *
     * @var CatalogExportService
     */
    protected CatalogExportService $catalogExportService;

    /**
     * The logger instance
     *
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @param CatalogExportService $catalogExportService
     */
    public function __construct(CatalogExportService $catalogExportService, LoggerInterface $logger)
    {
        $this->catalogExportService = $catalogExportService;
        $this->logger = $logger;
    }

    /**
     * Export catalog in AWS S3 storage in CSV format
     *
     * @return JsonResponse
     */
    public function exportCatalog(): JsonResponse
    {
        $this->logger->info(__('log.exp_click_successfully'), [
            'action' => 'catalog_export_initiated',
            'user_id' => auth()->id() ?? null,
            'ip' => request()->ip()
        ]);
        $result = $this->catalogExportService->exportCatalog();

        return response()->json(
            $result,
            $result['success'] ? ResponseAlias::HTTP_OK : ResponseAlias::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
