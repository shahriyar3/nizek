<?php

namespace App\Actions;

use App\Models\StockPrice;

class BatchInsertStockPricesAction
{
    public function handle(array $data): void
    {
        if (empty($data)) {
            return;
        }

        StockPrice::query()->insert($data);
    }
}
