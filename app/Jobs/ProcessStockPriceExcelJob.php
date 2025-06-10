<?php

namespace App\Jobs;

use App\Services\ExcelService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessStockPriceExcelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 300;

    public function __construct(
        private readonly string $filePath,
        private readonly int $companyId
    ) {}

    public function handle(ExcelService $excelService): void
    {
        try {
            $excelService->processStockPrices($this->filePath, $this->companyId);

            if (file_exists(storage_path('app/' . $this->filePath))) {
                unlink(storage_path('app/' . $this->filePath));
            }
        } catch (\Exception $e) {
            Log::error(__('Failed to process stock price Excel file'), [
                'file_path' => $this->filePath,
                'company_id' => $this->companyId,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}
