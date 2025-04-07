<?php

namespace App\Http\Controllers;

use App\Services\CurrencyRateService;
use Exception;
use Illuminate\Http\JsonResponse;

class CurrencyRateController extends Controller
{
    private CurrencyRateService $currencyRateService;

    /**
     * @param CurrencyRateService $currencyRateService
     */
    public function __construct(CurrencyRateService $currencyRateService)
    {
        $this->currencyRateService = $currencyRateService;
    }

    /**
     * @return JsonResponse
     *  @response
     *      "success": true,
     *      "message": "Rates updated successfully"
     *
     * @response 500
     *      "success": false,
     *      "message": "Error message"
     *
     * @throws Exception
     *
     *  Update currency rates manually
     *
     */
    public function updateRates(): JsonResponse
    {
        $result = $this->currencyRateService->fetchAndUpdateRates();
        $status = $result['success'] ? 200 : 500;

        return response()->json($result, $status);
    }

    /**
     *  Get current currency rates
     *
     * @return JsonResponse
     * @response {
     *      "success": true,
     *      "data": [
     *          {
     *              "currency_iso": "USD",
     *              "buy_rate": 3.102,
     *              "sale_rate": 3.112
     *          }
     *      ]
     *  }
     */
    public function getRates(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->currencyRateService->getAllRates()
        ]);
    }
}
