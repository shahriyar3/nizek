<?php

namespace Tests\Feature;

use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StockPriceUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_validates_required_fields()
    {
        $response = $this->postJson('/api/stock-prices/upload', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['file', 'company_id']);
    }

    public function test_validates_file_type()
    {
        $company = Company::factory()->create();

        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->postJson('/api/stock-prices/upload', [
            'file' => $file,
            'company_id' => $company->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['file']);
    }

    public function test_validates_company_exists()
    {
        $file = UploadedFile::fake()->create('stock_prices.xlsx', 100);

        $response = $this->postJson('/api/stock-prices/upload', [
            'company_id' => 999,
            'file' => $file
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['company_id']);
    }
}
