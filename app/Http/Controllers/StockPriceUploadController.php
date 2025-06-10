<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockPriceUploadRequest;
use App\Services\StockFileUploadService;
use Illuminate\Http\JsonResponse;

class StockPriceUploadController extends Controller
{
    public function __construct(
        private readonly StockFileUploadService $uploadService
    ) {}

    /**
     * @OA\Post(
     *     path="/api/stock-prices/upload",
     *     summary="Upload Excel file with stock prices",
     *     tags={"Stock Prices"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="file",
     *                     type="file",
     *                     format="binary",
     *                     description="Excel file containing stock prices"
     *                 ),
     *                 @OA\Property(
     *                     property="company_id",
     *                     type="integer",
     *                     description="Company identifier",
     *                     example="1"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="File uploaded successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="File uploaded successfully and is being processed."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function __invoke(StockPriceUploadRequest $request): JsonResponse
    {
        $this->uploadService->handleFileUpload($request->validated());

        return response()->json([
            'message' => __('File uploaded successfully and is being processed.')
        ]);
    }
}
