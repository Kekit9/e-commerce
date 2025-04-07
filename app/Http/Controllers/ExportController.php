<?php

namespace App\Http\Controllers;

use App\Services\CatalogExportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;


class ExportController extends Controller
{
    /**
     * The CatalogExport service instance
     *
     * @var CatalogExportService
     */
    protected CatalogExportService $catalogExportService;

    public function __construct(CatalogExportService $catalogExportService)
    {
        $this->catalogExportService = $catalogExportService;
    }

    /**
     *
     * Export catalog in AWS S3 storage in CSV format
     *
     */
    public function exportCatalog(): JsonResponse
    {
        Log::info('Export button clicked - starting process');
        $result = $this->catalogExportService->exportCatalog();

        return response()->json(
            $result,
            $result['success'] ? 200 : 500
        );
    }
}
