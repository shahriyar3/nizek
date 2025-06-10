<?php

namespace App\Actions;

use App\Models\StockPrice;
use Closure;

class RetrieveStockPriceOnOrBeforeDateAction
{
    public function handle(array $data, Closure $next): array
    {
        $companyId = $data['company_id'];
        $endDate = $data['end_date'];

        $endPriceRecord = StockPrice::query()
            ->where('company_id', $companyId)
            ->whereDate('date', '<=', $endDate)
            ->orderByDesc('date')
            ->first();

        $data['end_price_record'] = $endPriceRecord;

        return $next($data);
    }
}
