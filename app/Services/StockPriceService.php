<?php

namespace App\Services;

use App\Models\StockPrice;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class StockPriceService
{
    public function getStockPricesForPeriod(int $companyId, string $startDate, string $endDate): Collection
    {
        return StockPrice::query()
            ->where('company_id', $companyId)
            ->whereBetween('date', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->orderBy('date')
            ->get();
    }
}
