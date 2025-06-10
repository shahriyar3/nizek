<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\StockPrice;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockPriceFactory extends Factory
{
    protected $model = StockPrice::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'stock_price' => $this->faker->randomFloat(2, 10, 1000),
        ];
    }
}
