<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\StockPrice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StockPriceApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_period_data()
    {
        $company = Company::factory()->create();

        StockPrice::factory()->create([
            'company_id' => $company->id,
            'date' => '2024-01-01',
            'stock_price' => 100.50
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

    public function test_can_get_custom_period_data()
    {
        $company = Company::factory()->create();

        StockPrice::factory()->create([
            'company_id' => $company->id,
            'date' => '2024-01-01',
            'stock_price' => 100.50
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
}
