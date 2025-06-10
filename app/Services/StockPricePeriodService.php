<?php

namespace App\Services;

use App\Actions\RetrieveLatestStockPriceAction;
use App\Actions\RetrievePeriodStockPriceAction;
use App\Actions\CalculatePercentageChangeAction;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\App;
use App\Traits\CachableService;

class StockPricePeriodService
{
    use CachableService;

    public function handle(int $companyId): ?array
    {
        return $this->getCachedData(
            $this->generateCacheKey($companyId),
            function () use ($companyId) {
                $data = [
                    'company_id' => $companyId,
                    'latest_stock_price' => null,
                    'period_data' => [],
                ];

                $pipeline = App::make(Pipeline::class)
                    ->send($data)
                    ->through([
                        RetrieveLatestStockPriceAction::class,
                        RetrievePeriodStockPriceAction::class,
                        CalculatePercentageChangeAction::class,
                    ]);

                $result = $pipeline->thenReturn();

                return $result['period_data'] ?? null;
            }
        );
    }

    protected function generateCacheKey(int $companyId): string
    {
        return 'stock_prices_' . $companyId . '_periods';
    }
}
