<?php

namespace App\Service;


use App\Models\ResidentialNationality;
use App\Models\ResidentialRental;
use App\Models\ResidentialRentalPsf;
use App\Models\ResidentialTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;

class ResidentialService
{
    private static $config = null;

    public function __construct()
    {
        if (Cookie::has(GlobalConstant::REPORT_RESIDENTIAL_CONFIG_COOKIE)) {
            $this->config = Cookie::get(GlobalConstant::REPORT_RESIDENTIAL_CONFIG_COOKIE);
        }
    }

    public static function getTransactionProjectList($projectName)
    {

        $config = Cookie::get(GlobalConstant::REPORT_RESIDENTIAL_CONFIG_COOKIE);

        $limit_year = 5;
        $transaction_list = ResidentialTransaction::where('Project Name', $projectName)->get();
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
            if ($report_config['lower']) {
                $lower = $report_config['lower'];
            } else {
                $lower = null;
            }

            if ($report_config['upper']) {
                $upper = $report_config['upper'];
            } else {
                $upper = null;
            }

            if ($lower && $upper) {
                $transaction_list = $transaction_list->filter(function ($item) use ($lower, $upper) {
                    if ($lower != -1 && $upper != -1) {
                        return ((int)$lower < (float)$item['Area (sqm)'] * 10.76) && (float)$item['Area (sqm)'] * 10.76 < (int)$upper;
                    } elseif ($lower != -1 && $upper == -1) {
                        return (int)$lower < (float)$item['Area (sqm)'] * 10.76;
                    } elseif ($lower == -1 && $upper != -1) {
                        return (float)$item['Area (sqm)'] * 10.76 < (int)$upper;
                    } elseif ($lower == -1 && $upper == -1) {
                        return true;
                    }
                });
            }
        }


        return $transaction_list;
    }

    public static function getProfit($project_list)
    {

//        $project_list = ResidentialTransaction::getTransactionProjectList($project_name);

        $project_list = $project_list->map(function ($item) {
            $item['Sale Date'] = GlobalService::getNormalDateString($item['Sale Date']);
            return $item;
        });

        $address_group_items = $project_list->sortBy('Sale Date')->groupBy('Address');

        /* Calculate Profit */
        $profit_list = array();
        $unprofit_list = array();

        foreach ($address_group_items as $item) {
            if (count($item) > 0) {
                for ($i = 1; $i < count($item); $i++) {
                    $profit_value = $item[$i]['Transacted Price ($)'] - $item[$i - 1]['Transacted Price ($)'];
                    $holding_period = \Carbon\Carbon::parse($item[$i]['Sale Date'])->diffInDays(\Carbon\Carbon::parse($item[$i - 1]['Sale Date']));

                    if ($item[$i - 1]['Transacted Price ($)'] && $holding_period) {
                        $annualized = round(pow((($item[$i]['Transacted Price ($)'] - $item[$i - 1]['Transacted Price ($)']) / $item[$i - 1]['Transacted Price ($)']), 1 / ($holding_period / 365)), 2);
                    } else {
                        $annualized = null;
                    }

                    if ($annualized == null || $annualized == 0 || !$annualized) {
                        $annualized = '-';
                    }
                    $temp = array(
                        'sold_on' => ($item[$i]['Sale Date']),
                        'bought_on' => ($item[$i - 1]['Sale Date']),
                        'Address' => $item[$i - 1]['Address'],
                        'unit_area' => number_format(round($item[$i - 1]['Area (sqm)'] * 10.76)),
                        'sale_price_psf' => number_format($item[$i]['Unit Price ($ psf)']),
                        'purchase_price_psf' => number_format($item[$i - 1]['Unit Price ($ psf)']),
                        'profit' => number_format($profit_value),
                        'holding_period' => number_format($holding_period),
                        'annualized' => $annualized
                    );
                    if ($profit_value > 0) {
                        array_push($profit_list, $temp);
                    } else {
                        array_push($unprofit_list, $temp);
                    }
                }
            }
        }

        return array(
            'profit_list' => $profit_list,
            'unprofit_list' => $unprofit_list
        );
    }

    public static function getBuyerProfileData($project_name)
    {
        $nationalityData = ResidentialNationality::where('Project', $project_name)->first();
        if ($nationalityData) {
            $percent_data = array(
                'Singaporean' => round($nationalityData['Singaporean'] / (float)str_replace(',', '', $nationalityData['Total']) * 100, 2),
                'Pr' => round($nationalityData['Singapore Permanent Residents (PR)'] / (float)str_replace(',', '', $nationalityData['Total']) * 100, 2),
                'Foreigner (NPR)' => round($nationalityData['Foreigner (NPR)'] / (float)str_replace(',', '', $nationalityData['Total']) * 100, 2),
                'Company' => round($nationalityData['Company'] / (float)str_replace(',', '', $nationalityData['Total']) * 100, 2),
                'Unknown' => round($nationalityData['N.A'] / (float)str_replace(',', '', $nationalityData['Total']) * 100, 2)
            );
            return $percent_data;
        }
        return null;
    }

    public static function getRentalData($project_name)
    {
        $rental_items = ResidentialRental::where('Building/Project Name', $project_name)->get();
        return $rental_items;
    }

    public static function getHistoricalRental($address)
    {
        $street = GlobalService::getStreetFromAddress($address);
        $rental_data = ResidentialRentalPsf::where('Street Name', $street)->get();

        return $rental_data;
    }

    public static function getAveragePrice($project_list, $unit_size_group)
    {
        $size_item = explode('to', $unit_size_group);
        if (count($size_item) == 1) {
            return null;
        } else {
            $up_size = trim($size_item[1]);
            $down_size = trim($size_item[0]);

            $project_item = $project_list->where('Area (sqm)', '<=', $up_size / 10.76)->where('Area (sqm)', '>=', $down_size / 10.76);
            return round($project_item->average('Transacted Price ($)'), 2);
        }
    }


    public static function getTransactionPerUnit($project_list, $unit_size_group)
    {
        $size_item = explode('to', $unit_size_group);
        if (count($size_item) == 1) {
            return null;
        } else {
            $up_size = trim($size_item[1]);
            $down_size = trim($size_item[0]);

            $project_item = $project_list->where('Area (sqm)', '<=', $up_size / 10.76)->where('Area (sqm)', '>=', $down_size / 10.76);

            $address_group_items = $project_item->sortBy('Sale Date')->groupBy('Address');

            /* Calculate Profit */
            $transaction_Number = 0;

            foreach ($address_group_items as $item) {
                if (count($item) > 0) {
                    for ($i = 1; $i < count($item); $i++) {
                        $transaction_Number++;
                    }
                }
            }

            return $transaction_Number;
        }
    }

    public static function getNearByProperties($address)
    {
        $street = GlobalService::getStreetFromAddress($address);
        $nearby_items = ResidentialTransaction::where('Address', 'Like', '%' . $street . '%')->groupBy('Project Name')->get();
        return $nearby_items;
    }
}
