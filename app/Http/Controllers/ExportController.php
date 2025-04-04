<?php

namespace App\Http\Controllers;

use Aws\Exception\AwsException;
use Aws\Ses\SesClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\CatalogExported;
use App\Models\Product;
use League\Csv\CannotInsertRecord;
use League\Csv\Exception;
use League\Csv\Writer;

class ExportController extends Controller
{
    /**
     * @throws CannotInsertRecord
     * @throws Exception
     */
    public function exportCatalog(): JsonResponse
    {
        try {
            $products = Product::with(['maker', 'services'])->get();
            if ($products->isEmpty()) {
                throw new \RuntimeException('No products found for export');
            }

            $csv = Writer::createFromString('');
            $csv->insertOne(['ID', 'Name', 'Description', 'Price', 'Maker', 'Services']);

            $products->each(fn($product) => $csv->insertOne([
                $product->id,
                $product->name,
                $product->description,
                $product->price,
                $product->maker?->name ?? 'N/A',
                $product->services->pluck('name')->implode(', ') ?: 'N/A'
            ]));

            $filename = 'catalog_export_' . now()->format('Ymd_His') . '.csv';
            Storage::disk('s3')->put("exports/{$filename}", $csv->getContent());

            Mail::mailer('ses')
                ->to(env('ADMIN_EMAIL'))
                ->send(new CatalogExported(
                    $filename,
                    Storage::disk('s3')->temporaryUrl("exports/{$filename}", now()->addHour())
                ));

            return response()->json([
                'success' => true,
                'message' => 'Catalog exported and email sent',
                'file' => $filename,
                'url' => Storage::disk('s3')->url("exports/{$filename}")
            ]);

        } catch (\Exception $e) {
            Log::error('Export failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Export failed: ' . $e->getMessage(),
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }
}
