<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Actions\PrepareStockPriceRowAction;
use App\Actions\BatchInsertStockPricesAction;

readonly class StockPricesImport implements ToCollection, WithHeadingRow, WithChunkReading, WithBatchInserts, ShouldQueue
{
    public function __construct(private int $companyId)
    {
    }

    public function collection(Collection $collection): void
    {
        DB::transaction(function () use ($collection) {

            $batchInsertAction = new BatchInsertStockPricesAction();
            $prepareRowAction = new PrepareStockPriceRowAction();
            $batch = [];
            foreach ($collection as $row) {
                $preparedRowData = $prepareRowAction->handle($row->toArray(), $this->companyId);

                if ($preparedRowData) {
                    $batch[] = $preparedRowData;
                }
            }

            $batchInsertAction->handle($batch);

        });
    }

    public function chunkSize(): int
    {
        return 1;
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function headingRow(): int
    {
        return 8;
    }
}
