<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

trait CachableService
{
    protected function getCachedData(string $key, \Closure $callback, int $ttl = 3600): mixed
    {
        return Cache::remember($key, $ttl, $callback);
    }

    protected function remember(string $key, \Closure $callback, int $ttl = 3600): Collection
    {
        return Cache::remember($key, $ttl, $callback);
    }

    protected function generateCacheKey(int $companyId, ?string $startDate = null, ?string $endDate = null): string
    {
        $key = "stock_prices:{$companyId}";

        if ($startDate) {
            $key .= ":{$startDate}";
        }

        if ($endDate) {
            $key .= ":{$endDate}";
        }

        return $key;
    }
}
