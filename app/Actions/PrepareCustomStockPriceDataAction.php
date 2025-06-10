<?php

namespace App\Actions;

use Closure;

class PrepareCustomStockPriceDataAction
{
    public function handle(array $data, Closure $next): array
    {
        $startPriceRecord = $data['start_price_record'] ?? null;
        $endPriceRecord = $data['end_price_record'] ?? null;

        $customPeriodData = [
            'start_date' => $startPriceRecord ? $startPriceRecord->date : null,
            'end_date' => $endPriceRecord ? $endPriceRecord->date : null,
            'start_price' => $startPriceRecord ? $startPriceRecord->price : null,
            'end_price' => $endPriceRecord ? $endPriceRecord->price : null,
        ];

        $data['custom_period_data'] = $customPeriodData;

        return $next($data);
    }
}
