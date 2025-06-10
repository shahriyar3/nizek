<?php

namespace App\Actions;

use App\Imports\StockPricesImport;
use App\Jobs\ProcessExcelFileJob;
use Maatwebsite\Excel\Facades\Excel;

readonly class DispatchStockPriceProcessingJobAction
{
    public function handle(array $data, \Closure $next): array
    {
        if (!isset($data['file_path']) || !isset($data['company_id'])) {
            return $next($data);
        }

        ProcessExcelFileJob::dispatch(
            $data['file_path'],
            $data['company_id']
        );
//        Excel::import(new StockPricesImport($data['company_id']), 'storage/app/public/' . $data['file_path']);

        $data['job_dispatched'] = true;

        return $next($data);
    }
}
