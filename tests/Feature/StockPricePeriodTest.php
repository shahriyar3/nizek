<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\StockPrice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockPricePeriodTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_stock_prices_for_period()
    {
        $company = Company::factory()->create();

        StockPrice::factory()->create([
            'company_id' => $company->id,
            'date' => '2024-01-15',
            'stock_price' => 323.83
        ]);

        $response = $this->getJson("/api/stock-prices/period?company_id={$company->id}&start_date=2024-01-01&end_date=2024-01-31");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'company_id',
                        'date',
                        'stock_price',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);
    }

    public function test_validates_required_fields()
    {
        $response = $this->getJson('/api/stock-prices/period');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['company_id', 'start_date', 'end_date']);
    }

    public function test_validates_date_format()
    {
        $company = Company::factory()->create();

        $response = $this->getJson("/api/stock-prices/period?company_id={$company->id}&start_date=invalid&end_date=2024-01-31");

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['start_date']);
    }

    public function test_validates_date_range()
    {
        $company = Company::factory()->create();

        $response = $this->getJson("/api/stock-prices/period?company_id={$company->id}&start_date=2024-01-31&end_date=2024-01-01");

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['end_date']);
    }
}
