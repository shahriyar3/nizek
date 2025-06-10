<?php

namespace App\Actions;

use App\Models\StockPrice;
use Closure;
use Carbon\Carbon;

class RetrievePeriodStockPriceAction
{
    public function handle(array $data, Closure $next): array
    {
        $companyId = $data['company_id'];
        $latestStockPriceDate = $data['latest_stock_price']
            ? StockPrice::query()->where('company_id', $companyId)->orderByDesc('date')->first()?->date
            : null;

        if (!$latestStockPriceDate) {
            $data['period_data'] = [];
            return $next($data);
        }

        $endDate = Carbon::parse($latestStockPriceDate);
        $periods = [
            '1D' => $endDate->copy()->subDay(),
            '1W' => $endDate->copy()->subWeek(),
            '1M' => $endDate->copy()->subMonth(),
            '1Y' => $endDate->copy()->subYear(),
        ];

        $periodData = [];

        foreach ($periods as $periodName => $startDate) {
            $startPriceRecord = StockPrice::query()
                ->where('company_id', $companyId)
                ->where('date', '>=', $startDate->toDateString())
                ->orderBy('date')
                ->first();

            $endPriceRecord = StockPrice::query()
                ->where('company_id', $companyId)
                ->where('date', $endDate->toDateString())
                ->first();

            if ($startPriceRecord && $endPriceRecord) {
                $periodData[$periodName] = [
                    'period' => $periodName,
                    'start_date' => $startPriceRecord->date,
                    'end_date' => $endPriceRecord->date,
                    'start_price' => $startPriceRecord->price,
                    'end_price' => $endPriceRecord->price,
                ];
            }
        }

        $data['period_data'] = $periodData;

        return $next($data);
    }
}
