<?php

namespace App\Services;

use App\Interfaces\CatalogExportRepositoryInterface;

class CatalogExportService
{
    /**
     * The catalog export repository instance
     *
     * @var CatalogExportRepositoryInterface
     */
    protected CatalogExportRepositoryInterface $catalogExportRepository;

    /**
     * CatalogExportService constructor
     *
     * @param CatalogExportRepositoryInterface $catalogExportRepository The catalog export repository
     */
    public function __construct(CatalogExportRepositoryInterface $catalogExportRepository,)
    {
        $this->catalogExportRepository = $catalogExportRepository;
    }


    /**
     * Export product catalog to CSV and send to S3
     *
     * @return array
     */
    public function exportCatalog(): array
    {
        return $this->catalogExportRepository->exportCatalogToCsv();
    }
}
