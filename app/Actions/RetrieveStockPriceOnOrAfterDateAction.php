<?php

namespace App\Actions;

use App\Models\StockPrice;
use Closure;

class RetrieveStockPriceOnOrAfterDateAction
{
    public function handle(array $data, Closure $next): array
    {
        $companyId = $data['company_id'];
        $startDate = $data['start_date'];

        $startPriceRecord = StockPrice::query()
            ->where('company_id', $companyId)
            ->whereDate('date', '>=', $startDate)
            ->orderBy('date')
            ->first();

        $data['start_price_record'] = $startPriceRecord;

        return $next($data);
    }
}
