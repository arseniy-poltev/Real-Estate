<?php

namespace App\Service;
use App\Models\CommonTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;

class NonResidentialService
{
    private static $config = null;

    public function __construct()
    {
        if (Cookie::has(GlobalConstant::REPORT_LANDED_CONFIG_COOKIE)) {
            $this->config = Cookie::get(GlobalConstant::REPORT_LANDED_CONFIG_COOKIE);
        }
    }

    public static function filterProjectList($transaction_list)
    {
        $config = Cookie::get(GlobalConstant::REPORT_NON_RESIDENTIAL_COOKIE);
        $limit_year = 5;

        if ($config) {
            $report_config = json_decode($config, true);
            if ($report_config['timeframe']) {
                $limit_year = $report_config['timeframe'];
            } else {
                $limit_year = null;
            }
        }

        if ($limit_year) {
            $transaction_list = $transaction_list->filter(function ($item) use ($limit_year) {
                $item_date = GlobalService::getNormalDateString($item['Sale Date']);
                return Carbon::now()->diffInYears(Carbon::parse($item_date)) <= $limit_year;
            });
        }

        if ($config) {
            $report_config = json_decode($config, true);

            if (!isset($report_config['detached_house'])) {
                $transaction_list = $transaction_list->filter(function ($item) use ($report_config) {
                    return $item['Property Type'] != 'Detached House';
                });
            }

            if (!isset($report_config['semi_detached_house'])) {
                $transaction_list = $transaction_list->filter(function ($item) use ($report_config) {
                    return $item['Property Type'] != 'Semi-Detached House';
                });
            }

            if (!isset($report_config['terrace_house'])) {
                $transaction_list = $transaction_list->filter(function ($item) use ($report_config) {
                    return $item['Property Type'] != 'Terrace House';
                });
            }
        }

        if ($config) {
            $report_config = json_decode($config, true);
            if ($report_config['hide_unit_numbers'] == 1) {
                $transaction_list = $transaction_list->map(function ($item) use ($report_config) {
                    $address = $item['Address'];
                    if (count(explode('#', $address)) > 1) {
                        $item['Address_filtered'] = explode('#', $item['Address']);
                    } else {
                        $item['Address_filtered'] = GlobalService::getStreetFromAddress($item['Address']);
                    }

                    return $item;
                });
            }
        }

        return $transaction_list;
    }

    public static function getTransactionProjectList($projectName)
    {
        $transaction_list = CommonTransaction::where('Project Name', $projectName)->get();
        $transaction_list = self::filterProjectList($transaction_list);
        return $transaction_list;
    }
}
