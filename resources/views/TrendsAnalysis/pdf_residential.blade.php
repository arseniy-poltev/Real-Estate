@extends('layouts.pdf_template')

@section('styles')
    <style>
        .input-sm {
            height: 30px;
            line-height: 30px;
        }

        .label_item {
            text-align: left !important;
            padding-left: 50px !important;
        }

        .project-information-table tr td {
            border-bottom: 1px solid !important;
            border-left: 0 !important;
            border-right: 0 !important;
            border-top: 0 !important;
            font-size: 13px !important;
            padding: 5px !important;
        }

        .project-information-table tr td:first-child {
            font-weight: bold;
        }

        #buyer_profile_chart_div {
            width: 100%;
            height: 500px;
        }

        .configure_panel label {
            font-weight: bold;
        }


        input[type="checkbox"]:checked + label::before {
            background-color: #428bca;
            border-color: #428bca;
            margin-top: 3px !important;
        }

        .checkbox label::before {
            margin-top: 3px !important;
        }

        input[type="checkbox"]:checked + label::after {
            color: #fff;
        }

    </style>

    <link href="{{ public_path('plugin/checkbox/build.less.css') }}" rel="stylesheet">
@endsection

@section('page_title', 'REPORT | NON- RESIDENTIAL')

@section('contents')
    @php
        $project_list = \App\Models\ResidentialTransaction::getTransactionProjectList($project['Project Name']);
    @endphp

    @php
        $config_data_josn =  \Illuminate\Support\Facades\Cookie::get(\App\Service\GlobalConstant::REPORT_RESIDENTIAL_CONFIG_COOKIE);
        $config_data = json_decode($config_data_josn, true);

    @endphp
    <div class="content">
    </div>
    <div class="divider"></div>
    <!-- 3 Column Big Services -->
    <div class="services-big">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h3 class="title"> Project information&nbsp;</h3>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="content-box">
                        <h4 class="text-center">PROJECT INFORMATION</h4>
                        <table class="table project-information-table">
                            <tbody>
                            <tr>
                                <td>Project Name</td>
                                <td>{{ $project['Project Name'] }}</td>
                            </tr>
                            <tr>
                                <td>STREET NAME</td>
                                <td>{{ explode('#', $project['Address'])[0] }}</td>
                            </tr>
                            <tr>
                                <td>PROPERTY TYPE</td>
                                <td>{{ $project['Property Type'] }}</td>
                            </tr>
                            <tr>
                                <td>TENURE</td>
                                <td>{{ $project['Tenure'] }}</td>
                            </tr>
                            <tr>
                                <td>DISTRICT / PLANNING AREA</td>
                                <td>{{ 'D' . $project['Postal District'] . ' / ' . $project['Planning Area'] }}</td>
                            </tr>
                            <tr>
                                <td>COMPLETION</td>
                                <td>2019</td>
                            </tr>
                            <tr>
                                <td>NUMBER OF UNITS</td>
                                <td>{{ $project['No_of_Unit'] }} UNITS</td>
                            </tr>
                            <tr>
                                <td>INDICATIVE PRICE RANGE / AVERAGE*</td>
                                <td>
                                    S${{ $project_list->min('Unit Price ($ psf)') }}
                                    -
                                    S$ {{ $project_list->max('Unit Price ($ psf)') }}
                                    PSF /
                                    S$ {{ $project_list->average('Unit Price ($ psf)') }}
                                    PSF
                                </td>
                            </tr>
                            <tr>
                                <td>INDICATIVE RENTAL RANGE / AVERAGE*</td>
                                <td>
                                    S$ {{ $project_list->min('Unit Price ($ psf)') }}
                                    -
                                    S$ {{ $project_list->max('Unit Price ($ psf)') }}
                                    PSF PM /
                                    S$ {{ $project_list->average('Unit Price ($ psf)') }}
                                    PSF PM
                                </td>

                            </tr>
                            <tr>
                                <td>IMPLIED RENTAL YIELD</td>
                                <td>3.06%</td>
                            </tr>
                            <tr>
                                <td>HISTORICAL HIGH</td>
                                <td>S$ 1,350 PSF IN OCT 2018 FOR A 463-SQFT UNIT</td>
                            </tr>
                            <tr>
                                <td>INDICATIVE AVERAGE PRICE FROM HISTORICAL HIGH</td>
                                <td>-9.4%</td>
                            </tr>
                            <tr>
                                <td>HISTORICAL LOW</td>
                                <td>S$ 765 PSF IN JUL 2015 FOR A 1,550-SQFT UNIT</td>
                            </tr>
                            <tr>
                                <td>BUYER PROFILE BY STATUS#</td>
                                <td>SINGAPOREAN 80.0%, PR 16.8%, FOREIGNER 3.1%, COMPANY 0.0%</td>
                            </tr>
                            <tr>
                                <td>BUYER PROFILE BY PURCHASER ADDRESS#</td>
                                <td>-</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="content-box">
                        <h4 class="text-center">Location</h4>
                        <div id="maps" class="google-maps">
                        </div>
                    </div>
                </div>

                <!-- 3 Column Services End-->
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="divider"></div>
    <!-- Slogan Start-->

    <div class="slogan bottom-pad-small p-t-50 p-b-30">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h3 class="title"> Sales&nbsp;</h3>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="content-box">
                        {{--<h3 class="text-center">Location</h3>--}}
                        <table class="table-striped table-bordered table minimalist datatable-component" width="100%">
                            <thead>
                            <tr>
                                <th>Contract<br>date</th>
                                <th>Address</th>
                                <th>Type of<br>sale</th>
                                <th>Unit area<br>(sqft)</th>
                                <th>Type of<br>area</th>
                                <th>Price<br>(S$ psf)</th>
                                <th>Price<br>(S$)</th>
                                <th>Purchaser<br>address</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($project_list as $project_item)
                                <tr>
                                    <td>{{ \App\Service\GlobalService::getNormalDateString($project_item['Sale Date']) }}</td>
                                    <td>{{ $project_item['Address'] }}</td>
                                    <td>{{ $project_item['Type of Sale'] }}</td>
                                    <td>{{ $project_item['Area (sqm)'] * 10.76 }}</td>
                                    <td>{{ $project_item['Type of Area'] }}</td>
                                    <td>{{ $project_item['Unit Price ($ psf)'] }}</td>
                                    <td>{{ $project_item['Transacted Price ($)'] }}</td>
                                    <td>{{ $project_item['Purchaser Address Indicator'] }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <!-- Slogan End-->
    <div class="divider"></div>


    <div class="services-big">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h3 class="title">Bulk purchase and buyer profile&nbsp;</h3>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div id="buyer_profile_chart_div"></div>
                </div>
            </div>
        </div>
    </div>



    {{-- Calculate Profit --}}
    @php
        $profit_result = \App\Service\ResidentialService::getProfit($project['Project Name']);
        $profit_list = $profit_result['profit_list'];
        $unprofit_list = $profit_result['unprofit_list'];
    @endphp
    <div class="divider"></div>
    <div class="slogan bottom-pad-small p-t-50 p-b-30">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h3 class="title">Profitable and unprofitable transactions&nbsp;</h3>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h4 class="text-center">PROFITABLE TRANSACTIONS (TOTAL OF {{ count($profit_list) }}
                        TRANSACTIONS)</h4>
                    <div class="content-box">
                        @if(count($profit_list) > 0)
                            <table class="table-striped table-bordered table minimalist datatable-component" width="100%"
                                   id="atidph-table">
                                <thead>
                                <tr>
                                    <th class="headerSortUp">Sold on</th>
                                    <th class="">Address</th>
                                    <th class="">Unit area<br>(sqft)</th>
                                    <th class="">Sale price<br>(S$ psf)</th>
                                    <th class="">Bought on</th>
                                    <th class="">Purchase price<br>(S$ psf)</th>
                                    <th class="">Profit<br>(S$)</th>
                                    <th class="">Holding period<br>(days)</th>
                                    <th class="">Annualised<br>(%)</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($profit_list as $item)
                                    <tr>
                                        <td>{{ $item['sold_on'] }}</td>
                                        <td>{{ $item['Address'] }}</td>
                                        <td>{{ $item['unit_area'] }}</td>
                                        <td>{{ $item['sale_price_psf'] }}</td>
                                        <td>{{ $item['bought_on'] }}</td>
                                        <td>{{ $item['purchase_price_psf'] }}</td>
                                        <td>{{ $item['profit'] }}</td>
                                        <td>{{ $item['holding_period'] }}</td>
                                        <td>{{ $item['annualized'] }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
                <div class="clearfix"></div>


                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h4 class="text-center">UNPROFITABLE TRANSACTIONS
                        (TOTAL OF {{ count($unprofit_list) }} TRANSACTION)</h4>
                    <div class="content-box">
                        {{--<h3 class="text-center">Location</h3>--}}
                        @if(count($unprofit_list) > 0)
                            <table class="table-striped table-bordered table minimalist datatable-component" width="100%"
                                   id="atidph-table">
                                <thead>
                                <tr>
                                    <th class="headerSortUp">Sold<br>on</th>
                                    <th class="">Address</th>
                                    <th class="">Unit area<br>(sqft)</th>
                                    <th class="">Sale price<br>(S$ psf)</th>
                                    <th class="">Bought<br>on</th>
                                    <th class="">Purchase price<br>(S$ psf)</th>
                                    <th class="">Profit<br>(S$)</th>
                                    <th class="">Holding period<br>(days)</th>
                                    <th class="">Annualised<br>(%)</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($unprofit_list as $item)
                                    <tr>
                                        <td>{{ $item['sold_on'] }}</td>
                                        <td>{{ $item['Address'] }}</td>
                                        <td>{{ $item['unit_area'] }}</td>
                                        <td>{{ $item['sale_price_psf'] }}</td>
                                        <td>{{ $item['bought_on'] }}</td>
                                        <td>{{ $item['purchase_price_psf'] }}</td>
                                        <td>{{ $item['profit'] }}</td>
                                        <td>{{ $item['holding_period'] }}</td>
                                        <td>{{ $item['annualized'] }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>


    @php
        $residental_rental = \App\Service\ResidentialService::getRentalData($project['Project Name']);
        $average_rental = $residental_rental->groupBy('Floor Area (sq ft)');
    @endphp
    {{-- Rental --}}
    <div class="divider"></div>
    <div class="slogan bottom-pad-small p-t-50 p-b-30">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h3 class="title">Rental &nbsp;</h3>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h4 class="text-center">RENTAL CONTRACTS</h4>
                    <div class="content-box">
                        {{--<h3 class="text-center">Location</h3>--}}
                        <table class="table-striped table-bordered table minimalist datatable-component" width="100%">
                            <thead>
                            <tr>
                                <th class="headerSortUp">Lease Start</th>
                                <th class="">Street</th>
                                <th class="">Type</th>
                                <th class="">Unit size (sqft)</th>
                                <th class="">Number of bedrooms</th>
                                <th class="">Monthly rent (S$)</th>
                                <th class="">Monthly rent <br>(Est. S$ psf)</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($residental_rental as $item)
                                <tr>
                                    <td>{{ $item['Lease Commencement Date'] }}</td>
                                    <td>{{ $item['Street Name'] }}</td>
                                    <td>{{ $item['Type'] }}</td>
                                    <td>{{ $item['Floor Area (sq ft)'] }}</td>
                                    <td>{{ $item['No. of Bedroom(for Non-Landed Only)'] }}</td>
                                    <td>{{ $item['Monthly Gross Rent($)'] }}</td>
                                    @php
                                        if (strpos($item['Floor Area (sq ft)'], 'to')) {
                                            $monthly_rent_est = $item['Monthly Gross Rent($)']/(((int)trim(explode('to', $item['Floor Area (sq ft)'])[0]) + (int)trim(explode('to', $item['Floor Area (sq ft)'])[1]))/2);
                                        } else {
                                            $monthly_rent_est = null;
                                        }
                                    @endphp
                                    <td>{{ round($monthly_rent_est, 1) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h4 class="text-center">Average rental yield analysis</h4>
                    <div class="content-box">
                        {{--<h3 class="text-center">Location</h3>--}}
                        @if(count($average_rental) > 0)
                            <table class="table-striped table-bordered table minimalist datatable-component "
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>Unit size (sqft)</th>
                                    <th>Average monthly rent* (S$)</th>
                                    <th>No. of rental contracts*</th>
                                    <th>Average price* (S$)</th>
                                    <th>No. of transactions*</th>
                                    <th>Rental yield* (%)</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($average_rental as $item)
                                    <tr>
                                        <td>{{ $item[0]['Floor Area (sq ft)'] }}</td>
                                        <td>{{ round($item->average('Monthly Gross Rent($)'), 2) }}</td>
                                        <td>{{ $item->sum('Count') }}</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Get Historical QUARTERLY  Reantal --}}
    @php

        @endphp
    <div class="divider"></div>
    <div class="slogan bottom-pad-small p-t-50 p-b-30">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h3 class="title"> Historical quarterly &nbsp;</h3>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="content-box">
                        <h3 class="text-center">HISTORICAL QUARTERLY RENTAL</h3>
                        <div id="historical_rental_chart" class="google-maps">
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="content-box">
                        <h3 class="text-center">HISTORICAL QUARTERLY RENTAL</h3>
                        <table class="table project-information-table">
                            <tbody>
                            <tr>
                                <td>Project Name</td>
                                <td>HIGH PARK RESIDENCES</td>
                            </tr>
                            <tr>
                                <td>STREET NAME</td>
                                <td>FERNVALE ROAD</td>
                            </tr>
                            <tr>
                                <td>PROPERTY TYPE</td>
                                <td>APARTMENT</td>
                            </tr>
                            <tr>
                                <td>TENURE</td>
                                <td>99 YEARS LEASEHOLD</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>


                <!-- 3 Column Services End-->
                <div class="clearfix"></div>
            </div>
        </div>
    </div>


    {{-- Get Historical Reantal --}}
    @php
        $historical_rental = \App\Service\ResidentialService::getHistoricalRental($project['Address'])->groupBy('Lease Commencement Date');
    @endphp
    <div class="divider"></div>
    <div class="slogan bottom-pad-small p-t-50 p-b-30">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h3 class="title">Historical rental</h3>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h4 class="text-center">Historical rental
                        along {{ \App\Service\GlobalService::getStreetFromAddress($project['Address'])}}</h4>
                    <div class="content-box">
                        {{--<h3 class="text-center">Location</h3>--}}

                        @if(count($historical_rental) > 0)
                            <table
                                class="table-striped table-bordered table minimalist datatable-component datatable-component"
                                width="100%">
                                <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Street</th>
                                    <th>Type</th>
                                    <th>Lowest Rental (S$ psf pm)</th>
                                    <th>Rental 25th (S$ psf pm)</th>
                                    <th>Median Rental (S$ psf pm)</th>
                                    <th>Rental 75th (S$ psf pm)</th>
                                    <th>Highest Rental (S$ psf pm)</th>
                                    <th>Contracts</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($historical_rental as $item)
                                    <tr>
                                        <td>{{ $item[0]['Lease Commencement Date'] }}</td>
                                        <td>{{ $item[0]['Street Name'] }}</td>
                                        <td>{{ $item[0]['Type'] }}</td>
                                        <td>Sub Sale</td>
                                        <td>452</td>
                                        <td>Strata</td>
                                        <td>1,239</td>
                                        <td>560,000</td>
                                        <td>HDB</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Unit Size Distribution --}}
    <div class="divider"></div>
    <div class="slogan bottom-pad-small p-t-50 p-b-30">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h3 class="title">Unit size distribution &nbsp;</h3>
                </div>
                <h4 class="text-center">ESTIMATED UNIT SIZE DISTRIBUTION</h4>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="content-box">
                        <div id="unit_size_distribution">
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="content-box">
                        <table class="table project-information-table">
                            <tbody>
                            <tr>
                                <td>Project Name</td>
                                <td>HIGH PARK RESIDENCES</td>
                            </tr>
                            <tr>
                                <td>STREET NAME</td>
                                <td>FERNVALE ROAD</td>
                            </tr>
                            <tr>
                                <td>PROPERTY TYPE</td>
                                <td>APARTMENT</td>
                            </tr>
                            <tr>
                                <td>TENURE</td>
                                <td>99 YEARS LEASEHOLD</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>


                <!-- 3 Column Services End-->
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

    <div class="divider"></div>
    <div class="services-big">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h3 class="title"> UNIT SIZE DISTRIBUTION &nbsp;</h3>
                </div>

                <h4 class="text-center">HISTORICAL RENTAL ALONG FERNVALE ROAD</h4>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="content-box">
                        <div id="maps" class="google-maps">
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="content-box">
                        <table class="table project-information-table">
                            <tbody>
                            <tr>
                                <td>Project Name</td>
                                <td>HIGH PARK RESIDENCES</td>
                            </tr>
                            <tr>
                                <td>STREET NAME</td>
                                <td>FERNVALE ROAD</td>
                            </tr>
                            <tr>
                                <td>PROPERTY TYPE</td>
                                <td>APARTMENT</td>
                            </tr>
                            <tr>
                                <td>TENURE</td>
                                <td>99 YEARS LEASEHOLD</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>


                <!-- 3 Column Services End-->
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

    <div class="divider"></div>
    <div class="slogan p-t-50 p-b-30">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h3 class="title"> NEARBY PROPERTIES &nbsp;</h3>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="content-box">
                        <h4 class="text-center">PRICE COMPARISON (UP TO 50)</h4>
                        <div id="maps" class="google-maps">
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="content-box">
                        <h4 class="text-center">RENTAL COMPARISON (UP TO 50)</h4>
                        <div id="maps" class="google-maps">
                        </div>
                    </div>
                </div>


                <!-- 3 Column Services End-->
                <div class="clearfix"></div>
            </div>
        </div>
    </div>




@endsection

@section('scripts')
    <script src="{{ public_path('js/jquery.gmap.min.js') }}"></script>
    {{--<script src="http://maps.google.com/maps/api/js?sensor=false&key=AIzaSyABrKRwDHO6gVhgjSBkP7Z2s98ZgHjTDGM"></script>--}}
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAF_yg6f3ThxlypZ195ZT_tumExwJrut10"
            type="text/javascript"></script>
    <script src="{{ public_path('js/custom.js') }}"></script>
    <script src="https://www.amcharts.com/lib/4/core.js"></script>
    <script src="https://www.amcharts.com/lib/4/charts.js"></script>
    <script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>

    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>


    <script>

        $('#refresh_report').on('click', function () {
            var settig_value = {};
            $.each($('.report_setting_form').serializeArray(), function () {
                settig_value[this.name] = this.value;
            });

            console.log(settig_value);
        });

        /* Set Report Configuration */
            @php
                $config_data = \Illuminate\Support\Facades\Cookie::get(\App\Service\GlobalConstant::REPORT_RESIDENTIAL_CONFIG_COOKIE);
            @endphp
        var config_data = '{!! $config_data !!}';

        $.each(JSON.parse(config_data), function (key, value) {
            $('input[name="' + key + '"]').val(value);
            $('input[name="' + key + '"]').attr('checked', value);
            $('select[name="' + key + '"]').val(value);
        });

        /* End report Configuration */

        $('#save_report_settings').on('click', function () {
            $.ajax({
                url: "{{ url('/trends-and-analysis/residential/report/save_setting') }}",
                type: 'post',
                data: $('.report_setting_form').serializeArray(),
                success: function () {
                    window.location.reload(true);
                }
            })
        });

        function printer_friendly() {

        }

        function print_to_pdf() {

        }



        @php
            $profile_data = \App\Service\ResidentialService::getBuyerProfileData($project['Project Name']);
        @endphp

        am4core.ready(function () {

            // Themes begin
            am4core.useTheme(am4themes_animated);
            // Themes end

            // Create chart instance
            var chart = am4core.create("buyer_profile_chart_div", am4charts.PieChart);
            // Set data
            var selected;
            var types = [{
                type: "Singaporean",
                percent: '{!! $profile_data['Singaporean'] !!}',
                color: chart.colors.getIndex(16),
                subs: [{
                    type: "Singaporean",
                    percent: '{!! $profile_data['Singaporean'] !!}',
                }]
            }, {
                type: "Pr",
                percent: '{!! $profile_data['Pr'] !!}',
                color: chart.colors.getIndex(5),
                subs: [{
                    type: "Pr",
                    percent: '{!! $profile_data['Pr'] !!}',
                }]
            }, {
                type: "Foreigner",
                percent: '{!! $profile_data['Foreigner (NPR)'] !!}',
                color: chart.colors.getIndex(2),
                subs: [{
                    type: "Foreigner",
                    percent: '{!! $profile_data['Foreigner (NPR)'] !!}',
                }]
            }, {
                type: "Company",
                percent: '{!! $profile_data['Company'] !!}',
                color: chart.colors.getIndex(9),
                subs: [{
                    type: "Company",
                    percent: '{!! $profile_data['Company'] !!}',
                }]
            }, {
                type: "Unknown",
                percent: '{!! $profile_data['Unknown'] !!}',
                color: chart.colors.getIndex(12),
                subs: [{
                    type: "Unknown",
                    percent: '{!! $profile_data['Unknown'] !!}',
                }]
            }];

            // Add data
            chart.data = generateChartData();

            var pieSeries = chart.series.push(new am4charts.PieSeries());
            pieSeries.dataFields.value = "percent";
            pieSeries.dataFields.category = "type";
            pieSeries.slices.template.propertyFields.fill = "color";
            pieSeries.slices.template.propertyFields.isActive = "pulled";
            pieSeries.slices.template.strokeWidth = 0;

            function generateChartData() {
                var chartData = [];
                for (var i = 0; i < types.length; i++) {
                    if (i == selected) {
                        for (var x = 0; x < types[i].subs.length; x++) {
                            chartData.push({
                                type: types[i].subs[x].type,
                                percent: types[i].subs[x].percent,
                                color: types[i].color,
                                pulled: true
                            });
                        }
                    } else {
                        chartData.push({
                            type: types[i].type,
                            percent: types[i].percent,
                            color: types[i].color,
                            id: i
                        });
                    }
                }
                return chartData;
            }

            pieSeries.slices.template.events.on("hit", function (event) {
                if (event.target.dataItem.dataContext.id != undefined) {
                    selected = event.target.dataItem.dataContext.id;
                } else {
                    selected = undefined;
                }
                chart.data = generateChartData();
            });

        });

        am4core.ready(function () {

            // Themes begin
            am4core.useTheme(am4themes_animated);
            // Themes end

            // Create chart instance
            var chart = am4core.create("historical_rental_chart", am4charts.XYChart);

            // Add data
            chart.data = [{
                "year": "1930",
                "italy": 1,
                "germany": 5,
                "uk": 3
            }, {
                "year": "1934",
                "italy": 1,
                "germany": 2,
                "uk": 6
            }, {
                "year": "1938",
                "italy": 2,
                "germany": 3,
                "uk": 1
            }, {
                "year": "1950",
                "italy": 3,
                "germany": 4,
                "uk": 1
            }, {
                "year": "1954",
                "italy": 5,
                "germany": 1,
                "uk": 2
            }, {
                "year": "1958",
                "italy": 3,
                "germany": 2,
                "uk": 1
            }, {
                "year": "1962",
                "italy": 1,
                "germany": 2,
                "uk": 3
            }, {
                "year": "1966",
                "italy": 2,
                "germany": 1,
                "uk": 5
            }, {
                "year": "1970",
                "italy": 3,
                "germany": 5,
                "uk": 2
            }, {
                "year": "1974",
                "italy": 4,
                "germany": 3,
                "uk": 6
            }, {
                "year": "1978",
                "italy": 1,
                "germany": 2,
                "uk": 4
            }];

            // Create category axis
            var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "year";
            categoryAxis.renderer.opposite = false;

            // Create value axis
            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.renderer.inversed = false;
            valueAxis.title.text = "Place taken";
            valueAxis.renderer.minLabelPosition = 0.01;

            // Create series
            var series1 = chart.series.push(new am4charts.LineSeries());
            series1.dataFields.valueY = "italy";
            series1.dataFields.categoryX = "year";
            series1.name = "Italy";
            series1.strokeWidth = 3;
            series1.bullets.push(new am4charts.CircleBullet());
            series1.tooltipText = "Place taken by {name} in {categoryX}: {valueY}";
            series1.legendSettings.valueText = "{valueY}";
            series1.visible = false;

            var series2 = chart.series.push(new am4charts.LineSeries());
            series2.dataFields.valueY = "germany";
            series2.dataFields.categoryX = "year";
            series2.name = 'Germany';
            series2.strokeWidth = 3;
            series2.bullets.push(new am4charts.CircleBullet());
            series2.tooltipText = "Place taken by {name} in {categoryX}: {valueY}";
            series2.legendSettings.valueText = "{valueY}";

            var series3 = chart.series.push(new am4charts.LineSeries());
            series3.dataFields.valueY = "uk";
            series3.dataFields.categoryX = "year";
            series3.name = 'United Kingdom';
            series3.strokeWidth = 3;
            series3.bullets.push(new am4charts.CircleBullet());
            series3.tooltipText = "Place taken by {name} in {categoryX}: {valueY}";
            series3.legendSettings.valueText = "{valueY}";

            // Add chart cursor
            chart.cursor = new am4charts.XYCursor();
            chart.cursor.behavior = "zoomY";

            // Add legend
            chart.legend = new am4charts.Legend();

        });

        am4core.ready(function () {
            am4core.useTheme(am4themes_animated);
            // Themes end

            // Create chart instance
            var chart = am4core.create("unit_size_distribution", am4charts.XYChart);

            // Add data
            chart.data = [{
                "country": "USA",
                "visits": 2025
            }, {
                "country": "China",
                "visits": 1882
            }, {
                "country": "Japan",
                "visits": 1809
            }, {
                "country": "Germany",
                "visits": 1322
            }, {
                "country": "UK",
                "visits": 1122
            }, {
                "country": "France",
                "visits": 1114
            }, {
                "country": "India",
                "visits": 984
            }];

            // Create axes

            var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "country";
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.renderer.minGridDistance = 30;

            // categoryAxis.renderer.labels.template.adapter.add("dy", function (dy, target) {
            //     if (target.dataItem && target.dataItem.index & 2 == 2) {
            //         return dy + 25;
            //     }
            //     return dy;
            // });

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

            // Create series
            var series = chart.series.push(new am4charts.ColumnSeries());
            series.dataFields.valueY = "visits";
            series.dataFields.categoryX = "country";
            series.name = "Visits";
            series.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/]";
            series.columns.template.fillOpacity = .8;

            var columnTemplate = series.columns.template;
            columnTemplate.strokeWidth = 2;
            columnTemplate.strokeOpacity = 1;

        });
    </script>
@endsection
