<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\CatalogExportService;
use Illuminate\Http\JsonResponse;
use League\Csv\Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ExportController extends Controller
{
    /**
     * @param CatalogExportService $catalogExportService
     * @param LoggerInterface $logger
     */
    public function __construct(
        protected CatalogExportService $catalogExportService,
        protected LoggerInterface $logger
    ) {
    }

    /**
     * Export catalog in AWS S3 storage in CSV format
     *
     * @return JsonResponse
     *
     * @throws Exception
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
