<?php

namespace App\Actions;

use Illuminate\Http\UploadedFile;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Storage;

class StoreUploadedFileAction
{
    public function handle(array $data, \Closure $next): array
    {
        $file = request()->file('file')->store('stock_uploads', 'public');

        $data['file_path'] = $file;

        return $next($data);
    }
}
