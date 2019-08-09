<?php

namespace App\Service;
use App\Models\CommonRental;
use App\Models\CommonTransaction;
use App\Models\ResidentialNationality;
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
                $item_date = $item['Contract Date'];
                return Carbon::now()->diffInYears(Carbon::parse($item_date)) <= $limit_year;
            });
        }

        if ($config) {
            $report_config = json_decode($config, true);

            if (!isset($report_config['office_check'])) {
                $transaction_list = $transaction_list->filter(function ($item) use ($report_config) {
                    return $item['Property Type'] != 'Office';
                });
            }

            if (!isset($report_config['shop_house_check'])) {
                $transaction_list = $transaction_list->filter(function ($item) use ($report_config) {
                    return $item['Property Type'] != 'Shop House';
                });
            }

            if (!isset($report_config['retail_check'])) {
                $transaction_list = $transaction_list->filter(function ($item) use ($report_config) {
                    return $item['Property Type'] != 'Retail';
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

    public static function getTransactionProjectList($projectName)
    {
        $transaction_list = CommonTransaction::where('Project Name', $projectName)->get();
        $transaction_list = self::filterProjectList($transaction_list);
        return $transaction_list;
    }

    public static function getProfit($project_list)
    {

        $address_group_items = $project_list->sortBy('Contract Date')->groupBy('Address');

        /* Calculate Profit */
        $profit_list = array();
        $unprofit_list = array();

        foreach ($address_group_items as $item) {
            if (count($item) > 0) {
                for ($i = 1; $i < count($item); $i++) {
                    $profit_value = $item[$i]['Transacted Price ($)'] - $item[$i - 1]['Transacted Price ($)'];
                    $holding_period = \Carbon\Carbon::parse($item[$i]['Contract Date'])->diffInDays(\Carbon\Carbon::parse($item[$i - 1]['Contract Date']));

                    if ($item[$i - 1]['Transacted Price ($)'] && $holding_period) {
                        $annualized = round(pow((($item[$i]['Transacted Price ($)'] - $item[$i - 1]['Transacted Price ($)']) / $item[$i - 1]['Transacted Price ($)']), 1 / ($holding_period / 365)), 2);
                    } else {
                        $annualized = null;
                    }

                    if ($annualized == null || $annualized == 0 || !$annualized) {
                        $annualized = '-';
                    }
                    $temp = array(
                        'sold_on' => ($item[$i]['Contract Date']),
                        'bought_on' => ($item[$i - 1]['Contract Date']),
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


    public static function getHistoricalRental($address)
    {
        $street = GlobalService::getStreetFromAddress($address);
        $rental_data = CommonRental::where('Street', $street)->get();

        return $rental_data;
    }

    public static function getRentalData($address)
    {
        $street_name = GlobalService::getStreetFromAddress($address);
        $rental_items = CommonRental::where('Street', $street_name)->get();
        return $rental_items;
    }

    public static function getNearByProperties($address)
    {
        $street = GlobalService::getStreetFromAddress($address);
        $nearby_items = CommonTransaction::where('Address', 'Like', '%' . $street . '%')->groupBy('Project Name')->get();
        return $nearby_items;
    }

    public static function getBuyerProfileData($project_name)
    {
        $nationalityData = ResidentialNationality::where('Project', $project_name)->first();
        if ($nationalityData) {
            $percent_data = array(
                'Singaporean' => round(str_replace(',', '', $nationalityData['Singaporean']) / (float)str_replace(',', '', $nationalityData['Total']) * 100, 2),
                'Pr' => round(str_replace(',', '', $nationalityData['Singaporean']) / (float)str_replace(',', '', $nationalityData['Total']) * 100, 2),
                'Foreigner (NPR)' => round(str_replace(',', '', $nationalityData['Foreigner (NPR)']) / (float)str_replace(',', '', $nationalityData['Total']) * 100, 2),
                'Company' => round(str_replace(',', '', $nationalityData['Company'])/ (float)str_replace(',', '', $nationalityData['Total']) * 100, 2),
                'Unknown' => round(str_replace(',', '', $nationalityData['N.A']) / (float)str_replace(',', '', $nationalityData['Total']) * 100, 2)
            );
            return $percent_data;
        }
        return null;
    }
}
