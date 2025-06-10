<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockPricePeriodResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'period' => $this['period'],
            'start_date' => $this['start_date'],
            'end_date' => $this['end_date'],
            'start_price' => $this['start_price'],
            'end_price' => $this['end_price'],
            'percentage_change' => $this['percentage_change']
        ];
    }
}
