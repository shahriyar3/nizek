<?php

namespace App\Jobs;

use App\Imports\StockPricesImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ProcessExcelFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected string $filePath, protected int $companyId)
    {
        $this->onQueue('excel');
    }

    public function handle(): void
    {
        try {
            Excel::import(new StockPricesImport($this->companyId), ('storage/app/public/' . $this->filePath));
            if (file_exists('storage/app/public/' . $this->filePath)) {
                unlink('storage/app/public/' . $this->filePath);
            }

            Log::info('Excel file processed successfully', ['file' => $this->filePath]);
        } catch (\Exception $e) {
            Log::error('Error processing Excel file', [
                'file' => $this->filePath,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
