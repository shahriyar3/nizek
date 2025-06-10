<?php

namespace App\Actions;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PrepareStockPriceRowAction
{
    public function handle(array $row, int $companyId): ?array
    {
        if (!isset($row['date'], $row['stock_price'])) {
            Log::warning('Skipping row due to missing date or stock_price keys', ['row' => $row]);
            return null;
        }

        try {
            $date = Carbon::createFromFormat('m/d/y', $row['date'])->toDateString();
        } catch (\Exception $e) {
            Log::error('Invalid date format in Excel row', ['row' => $row, 'error' => $e->getMessage()]);
            return null;
        }

        $price = (float) $row['stock_price'];

        if ($price <= 0) {
             Log::warning('Skipping row due to invalid price value', ['row' => $row]);
             return null;
        }

        return [
            'company_id' => $companyId,
            'date' => $date,
            'stock_price' => $price,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
