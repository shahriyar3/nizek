<?php

namespace App\Actions;

use Closure;

class CalculatePercentageChangeAction
{
    public function handle(array $data, Closure $next): array
    {
        if (isset($data['period_data']) && is_array($data['period_data'])) {
            foreach ($data['period_data'] as &$period) {
                $startPrice = $period['start_price'] ?? null;
                $endPrice = $period['end_price'] ?? null;

                if ($startPrice !== null && $endPrice !== null && $startPrice != 0) {
                    $percentageChange = (($endPrice - $startPrice) / $startPrice) * 100;
                    $period['percentage_change'] = round($percentageChange, 2);
                } else {
                    $period['percentage_change'] = null;
                }
            }
            unset($period);
        }

        if (isset($data['custom_period_data']) && is_array($data['custom_period_data'])) {
             $startPrice = $data['custom_period_data']['start_price'] ?? null;
             $endPrice = $data['custom_period_data']['end_price'] ?? null;

             if ($startPrice !== null && $endPrice !== null && $startPrice != 0) {
                 $percentageChange = (($endPrice - $startPrice) / $startPrice) * 100;
                 $data['custom_period_data']['percentage_change'] = round($percentageChange, 2);
             } else {
                 $data['custom_period_data']['percentage_change'] = null;
             }
        }

        return $next($data);
    }
}
