<?php

namespace App\Http\Controllers;

use App\Services\CatalogExportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
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
     * @param CatalogExportService $catalogExportService
     */
    public function __construct(CatalogExportService $catalogExportService)
    {
        $this->catalogExportService = $catalogExportService;
    }

    /**
     * Export catalog in AWS S3 storage in CSV format
     */
    public function exportCatalog(): JsonResponse
    {
        Log::info( __('log.exp_click_successfully'));
        $result = $this->catalogExportService->exportCatalog();

        return response()->json(
            $result,
            $result['success'] ? ResponseAlias::HTTP_OK : ResponseAlias::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
