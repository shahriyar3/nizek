<?php

namespace App\Services;

use App\Actions\StoreUploadedFileAction;
use App\Actions\DispatchStockPriceProcessingJobAction;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\App;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

readonly class StockFileUploadService
{
    public function __construct(
        private Pipeline $pipeline
    ) {}

    public function handleFileUpload(array $data): void
    {
        $this->pipeline->send($data)
            ->through([
                StoreUploadedFileAction::class,
                DispatchStockPriceProcessingJobAction::class,
            ])
            ->thenReturn();
    }
}
