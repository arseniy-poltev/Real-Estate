<?php
/**
 * Created by PhpStorm.
 * User: HAOJIE
 * Date: 8/5/2019
 * Time: 3:16 PM
 */

namespace App\Service;


use App\Models\LandedRental;
use App\Models\LandedRentalCount;
use App\Models\LandedRentalPsf;
use App\Models\LandedTransaction;
use App\Models\ResidentialNationality;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;

class LandedService
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
        $config = Cookie::get(GlobalConstant::REPORT_LANDED_CONFIG_COOKIE);
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


        $transaction_list = LandedTransaction::where('Project Name', $projectName)->get();

        $transaction_list = self::filterProjectList($transaction_list);
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
                'Singaporean' => round($nationalityData['Singaporean']/(float)str_replace(',', '', $nationalityData['Total']) * 100, 2),
                'Pr' => round($nationalityData['Singapore Permanent Residents (PR)']/(float)str_replace(',', '', $nationalityData['Total']) * 100, 2),
                'Foreigner (NPR)' => round($nationalityData['Foreigner (NPR)']/(float)str_replace(',', '', $nationalityData['Total']) * 100, 2),
                'Company' => round($nationalityData['Company']/(float)str_replace(',', '', $nationalityData['Total']) * 100, 2),
                'Unknown' => round($nationalityData['N.A']/(float)str_replace(',', '', $nationalityData['Total']) * 100, 2)
            );
            return $percent_data;
        }
        return null;
    }

    public static function getRentalData($project_name)
    {
        $rental_items = LandedRental::where('Building/Project Name', $project_name)->get();
        $config = Cookie::get(GlobalConstant::REPORT_LANDED_CONFIG_COOKIE);

        if ($config) {
            $report_config = json_decode($config, true);


            if (!isset($report_config['detached_house'])) {
                $rental_items = $rental_items->filter(function ($item) use ($report_config) {
                    return $item['Type'] != 'Detached House';
                })->values();
            }


            if (!isset($report_config['semi_detached_house'])) {
                $rental_items = $rental_items->filter(function ($item) use ($report_config) {
                    return $item['Type'] != 'Semi-Detached House';
                })->values();
            }


            if (!isset($report_config['terrace_house'])) {
                $rental_items = $rental_items->filter(function ($item) use ($report_config) {
                    return $item['Type'] != 'Terrace House';
                })->values();
            }
        }


        return $rental_items;
    }

    public static function getAveragePrice($project_list, $unit_size_group)
    {
        $size_item = explode('to', $unit_size_group);
        if (count($size_item) == 1) {
            return null;
        } else {
            $up_size = trim($size_item[1]);
            $down_size = trim($size_item[0]);

            $project_item = $project_list->where('Area (sqm)', '<=' ,$up_size/10.76)->where('Area (sqm)', '>=' ,$down_size/10.76);
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

            $project_item = $project_list->where('Area (sqm)', '<=' ,$up_size/10.76)->where('Area (sqm)', '>=' ,$down_size/10.76);

            $address_group_items = $project_item->sortBy('Sale Date')->groupBy('Address');

            /* Calculate Profit */
            $transaction_Number = 0;

            foreach ($address_group_items as $item) {
                if (count($item) > 0) {
                    for ($i = 1; $i < count($item); $i++) {
                        $transaction_Number ++;
                    }
                }
            }

            return $transaction_Number;
        }
    }

    public static function getNearByProperties($address) {
        $street = GlobalService::getStreetFromAddress($address);
        $nearby_items = LandedTransaction::where('Address', 'Like', '%' . $street . '%')->groupBy('Project Name')->get();
        return $nearby_items;
    }

    public static function getHistoricalRental($address)
    {
        $street = GlobalService::getStreetFromAddress($address);
        $rental_data = LandedRentalPsf::where('Street Name', $street)->get();

        return $rental_data;
    }
}
