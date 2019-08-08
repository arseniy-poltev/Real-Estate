<?php

namespace App\Service;


use App\Models\CommonRental;
use App\Models\CommonTransaction;

class CommercialService
{
    public static function getProfit($project_name) {

        $project_list = CommonTransaction::getTransactionProjectList($project_name);
        $address_group_items = $project_list->sortBy('Contract Date')->groupBy('Address');
        /* Calculate Profit */
        $profit_list = array();
        $unprofit_list = array();

        foreach ($address_group_items as $item) {
            if (count($item) > 0) {
                for ($i = 1; $i < count($item); $i++) {
                    $profit_value = $item[$i]['Transacted Price ($)'] - $item[$i - 1]['Transacted Price ($)'];
                    $temp = array(
                        'sold_on' => $item[$i]['Contract Date'],
                        'bought_on' => $item[$i-1]['Contract Date'],
                        'Address' => $item[$i-1]['Address'],
                        'unit_area' => $item[$i-1]['Area (sqm)'] * 10.76,
                        'sale_price_psf' => $item[$i]['Unit Price ($ psf)'],
                        'purchase_price_psf' => $item[$i-1]['Unit Price ($ psf)'],
                        'profit' => $profit_value,
                        'holding_period' => \Carbon\Carbon::parse($item[$i]['Contract Date'])->diffInDays(\Carbon\Carbon::parse($item[$i-1]['Contract Date'])),
                        'annualized' => '---'
                    );
                    if($profit_value > 0) {
                        array_push($profit_list, $temp);
                    }  else {
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

    public static function getHistoricalRental($address) {
        $street = GlobalService::getStreetFromAddress($address);
        $rental_list = CommonRental::where('Street', $street)->get();
        return $rental_list;
    }

    public static function getNearByProperties($address) {
        $street = GlobalService::getStreetFromAddress($address);
        $nearby_items = CommonTransaction::where('Address', 'Like', '%' . $street . '%')->groupBy('Project Name')->get();
        return $nearby_items;
    }
}
