<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockPricePeriodRequest;
use App\Services\StockPriceService;
use Illuminate\Http\JsonResponse;

class StockPricePeriodController extends Controller
{
    public function __construct(
        private readonly StockPriceService $stockPriceService
    ) {}

    /**
     * @OA\Get(
     *     path="/api/stock-prices/periods",
     *     summary="Get stock price data for predefined periods",
     *     tags={"Stock Prices"},
     *     @OA\Parameter(
     *         name="company_id",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="period", type="string", example="1D"),
     *                     @OA\Property(property="start_date", type="string", format="date", example="2024-04-29"),
     *                     @OA\Property(property="end_date", type="string", format="date", example="2024-04-30"),
     *                     @OA\Property(property="start_price", type="number", format="float", example=163.76),
     *                     @OA\Property(property="end_price", type="number", format="float", example=161.84),
     *                     @OA\Property(property="percentage_change", type="number", format="float", example=-1.17)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function __invoke(StockPricePeriodRequest $request): JsonResponse
    {
        $stockPrices = $this->stockPriceService->getStockPricesForPeriod(
            $request->validated('company_id'),
            $request->validated('start_date'),
            $request->validated('end_date')
        );

        return response()->json(['data' => $stockPrices]);
    }
}
