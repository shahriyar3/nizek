<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockPriceCustomRequest;
use App\Services\StockPriceCustomService;
use Illuminate\Http\JsonResponse;

class StockPriceCustomController extends Controller
{
    public function __construct(
        private readonly StockPriceCustomService $stockPriceCustomService
    ) {}

    /**
     * @OA\Get(
     *     path="/api/stock-prices/custom",
     *     summary="Get stock price data for custom period",
     *     tags={"Stock Prices"},
     *     @OA\Parameter(
     *         name="company_id",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="start_date", type="string", format="date", example="2024-01-01"),
     *                 @OA\Property(property="end_date", type="string", format="date", example="2024-04-30"),
     *                 @OA\Property(property="start_price", type="number", format="float", example=145.60),
     *                 @OA\Property(property="end_price", type="number", format="float", example=161.84),
     *                 @OA\Property(property="percentage_change", type="number", format="float", example=11.15)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function __invoke(StockPriceCustomRequest $request): JsonResponse
    {
        [$stockPrices, $cacheHit] = $this->stockPriceCustomService->handle(
            $request->validated('company_id'),
            $request->validated('start_date'),
            $request->validated('end_date'),
            $request->validated('interval')
        );

        return response()->json(['data' => $stockPrices])
            ->header('X-Cache', $cacheHit ? 'HIT' : 'MISS');
    }
}
