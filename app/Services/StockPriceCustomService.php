<?php

namespace App\Services;

use App\Models\StockPrice;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class StockPriceCustomService
{
    public function handle(int $companyId, string $startDate, string $endDate, string $interval): array
    {
        $cacheKey = $this->generateCacheKey($companyId, $startDate, $endDate, $interval);
        $cacheHit = false;
        $data = Cache::get($cacheKey);
        if ($data !== null) {
            $cacheHit = true;
        } else {
            $stockPrices = StockPrice::query()
                ->where('company_id', $companyId)
                ->whereBetween('date', [
                    Carbon::parse($startDate)->startOfDay(),
                    Carbon::parse($endDate)->endOfDay()
                ])
                ->orderBy('date')
                ->get();
            $data = $this->aggregateByInterval($stockPrices, $interval);
            Cache::put($cacheKey, $data, 3600); // cache for 1 hour
        }
        return [$data, $cacheHit];
    }

    private function aggregateByInterval(Collection $stockPrices, string $interval): Collection
    {
        $interval = $interval === 'weekly' ? 'week' : $interval;

        return $stockPrices->groupBy(function ($stockPrice) use ($interval) {
            return Carbon::parse($stockPrice->date)->startOf($interval)->format('Y-m-d');
        })->map(function ($group) {
            return [
                'date' => $group->first()->date,
                'stock_price' => $group->avg('stock_price')
            ];
        })->values();
    }

    private function generateCacheKey(int $companyId, string $startDate, string $endDate, string $interval): string
    {
        return "stock_prices:{$companyId}:{$startDate}:{$endDate}:{$interval}";
    }
}
