<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\StockPrice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockPriceCustomTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_custom_stock_prices()
    {
        $company = Company::factory()->create();

        StockPrice::factory()->create([
            'company_id' => $company->id,
            'date' => '2024-01-15',
            'stock_price' => 895.64
        ]);

        $response = $this->getJson("/api/stock-prices/custom?company_id={$company->id}&start_date=2024-01-01&end_date=2024-01-31&interval=weekly");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'date',
                        'stock_price'
                    ]
                ]
            ]);
    }

    public function test_validates_required_fields()
    {
        $response = $this->getJson('/api/stock-prices/custom');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['company_id', 'start_date', 'end_date', 'interval']);
    }

    public function test_validates_interval_values()
    {
        $company = Company::factory()->create();

        $response = $this->getJson("/api/stock-prices/custom?company_id={$company->id}&start_date=2024-01-01&end_date=2024-01-31&interval=invalid");

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['interval']);
    }

    public function test_uses_cache_for_identical_requests()
    {
        $company = Company::factory()->create();

        StockPrice::factory()->create([
            'company_id' => $company->id,
            'date' => '2024-01-15',
            'stock_price' => 526.69
        ]);

        $this->getJson("/api/stock-prices/custom?company_id={$company->id}&start_date=2024-01-01&end_date=2024-01-31&interval=weekly");

        $response = $this->getJson("/api/stock-prices/custom?company_id={$company->id}&start_date=2024-01-01&end_date=2024-01-31&interval=weekly");

        $response->assertStatus(200)
            ->assertHeader('X-Cache', 'HIT');
    }
}
