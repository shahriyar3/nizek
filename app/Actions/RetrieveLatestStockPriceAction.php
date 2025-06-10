<?php

namespace App\Actions;

use App\Models\StockPrice;
use Closure;

class RetrieveLatestStockPriceAction
{
    public function handle(array $data, Closure $next): array
    {
        $companyId = $data['company_id'];

        $latestStockPrice = StockPrice::query()->where('company_id', $companyId)
            ->orderByDesc('date')
            ->first();

        $data['latest_stock_price'] = $latestStockPrice ? $latestStockPrice->price : null;

        return $next($data);
    }
}
