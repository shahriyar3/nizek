<?php

use App\Http\Controllers\StockPriceController;
use App\Http\Controllers\StockPriceCustomController;
use App\Http\Controllers\StockPricePeriodController;
use App\Http\Controllers\StockPriceUploadController;
use Illuminate\Support\Facades\Route;

Route::get('test', function () {
    return response()->json(['message' => 'API is working']);
});

Route::prefix('stock-prices')->group(function () {
    Route::post('/upload', StockPriceUploadController::class);
    Route::get('/period', StockPricePeriodController::class);
    Route::get('/custom', StockPriceCustomController::class);
});
