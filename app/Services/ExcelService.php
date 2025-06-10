<?php

namespace App\Services;

use App\Models\StockPrice;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelService
{
    public function processStockPrices(string $filePath, int $companyId): void
    {
        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load(Storage::path($filePath));
        $worksheet = $spreadsheet->getActiveSheet();

        foreach ($worksheet->getRowIterator(2) as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $cells = [];
            foreach ($cellIterator as $cell) {
                $cells[] = $cell->getValue();
            }

            if (count($cells) >= 2) {
                StockPrice::query()->insert([
                    'company_id' => $companyId,
                    'date' => $cells[0],
                    'stock_price' => $cells[1],
                    'created_at' => now()->toDateTimeString(),
                    'updated_at' => now()->toDateTimeString(),
                ]);
            }
        }

        Storage::delete($filePath);
    }
}
