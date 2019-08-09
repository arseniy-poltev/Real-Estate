@extends('layouts.main')

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

        g[aria-labelledby="id-66-title"] {
            display: none;
        }

        #recent_transaction, #all_past_transaction {
            font-size: 11px;
        }

        #all_past_transaction {

        }
    </style>

    <link href="{{ asset('plugin/checkbox/build.less.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css" rel="stylesheet">
@endsection

@section('page_title', 'REPORT | COMMERCIAL / INDUSTRIAL')

@section('contents')

    @php
        // Get Config Date
    $config_data_josn =  \Illuminate\Support\Facades\Cookie::get(\App\Service\GlobalConstant::REPORT_NON_RESIDENTIAL_COOKIE);
    $config_data = json_decode($config_data_josn, true);
    if ($config_data) {
        if ($config_data['timeframe']) {
            $timeframe = 'Last ' . $config_data['timeframe'] . ' Years';
        } else {
            $timeframe = 'All Years';
        }
    } else {
        $timeframe = 'Last 5 Years';
    }

    // Get Project List
     $project_detail = \App\Service\GlobalService::getProject($project['Project Name']);
     $project_list = \App\Service\NonResidentialService::getTransactionProjectList($project['Project Name']);
    // Project List Last 6 month
     $project_list_6_month = $project_list->filter(function ($item){
           return \Carbon\Carbon::parse($item['Contract Date'])->diffInMonths(\Carbon\Carbon::now()) <= 6;
     })->values();


        // Get Rental for this project
    $residental_rental = \App\Service\NonResidentialService::getRentalData($project['Address']);
    $residental_rental = $residental_rental->map(function ($item) {
        if ($item['Floor Area ll']) {
         $item['rental'] = $item['Monthly Gross Rent($)']/$item['Floor Area ll'];
        } else {
         $item['rental'] = null;
        }

         return $item;
    });
    $residental_rental_6_month = $residental_rental;

    $nearby_items = \App\Service\NonResidentialService::getNearByProperties($project['Address']);
    $nearby_items = \App\Service\GlobalService::getDistanceAndMarker($nearby_items, $project_detail);

    // Get Buyer Profile for this project
    $profile_data = \App\Service\NonResidentialService::getBuyerProfileData($project['Project Name']);
    @endphp
    <!-- Title, Breadcrumb Start-->
    <div class="breadcrumb-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                    <h2 class="title">HIGH PARK RESIDENCES</h2>
                </div>
                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                    <div class="breadcrumbs pull-right">
                        <ul>
                            <li>You are here:</li>
                            <li><a href="index.html">Residential and Analysis</a></li>
                            {{-- <li><a href="#">Pages</a></li> --}}
                            <li>Report</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Title, Breadcrumb End-->
    <!-- Main Content start-->

    {{-- Project Configuration --}}
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="posts-block col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <article>
                        <h3 class="title">Overview</h3>
                        <div class="post-content">
                            <div class="accordionMod panel-group">
                                <div class="accordion-item">
                                    <h4 class="accordion-toggle">CONFIGURE REPORT</h4>
                                    <section class="accordion-inner panel-body form-horizontal configure_panel">
                                        <form method="POST"
                                              action="{{ url('trends-and-analysis/non-residential/report/refresh_setting') }}"
                                              class="report_setting_form">
                                            @csrf
                                            <div class="form-group">
                                                <label class="control-label col-sm-2 text-left label_item">1. TIME
                                                    PERIOD:</label>
                                                <div class="col-sm-2">
                                                    <select id="timeframe" name="timeframe"
                                                            class="form-control input-sm">
                                                        <option value="">All data</option>
                                                        <option value="10">Last 10 years</option>
                                                        <option value="9">Last 9 years</option>
                                                        <option value="8">Last 8 years</option>
                                                        <option value="7">Last 7 years</option>
                                                        <option value="6">Last 6 years</option>
                                                        <option value="5" selected="">Last 5 years</option>
                                                        <option value="4">Last 4 years</option>
                                                        <option value="3">Last 3 years</option>
                                                        <option value="2">Last 2 years</option>
                                                        <option value="1">Last 1 year</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-4 text-left label_item">2. PROPERTY
                                                    TYPES:</label>

                                            </div>

                                            <div class="form-group" style="padding: 0 30px;">
                                                <div class="col-md-3 col-sm-3 checkbox">
                                                    <input type="checkbox" id="office_check" name="office_check"
                                                           @if(isset($config_data) && isset($config_data['office_check']))
                                                           {{ $config_data['office_check'] }}
                                                           @elseif(isset($config_data) && !isset($config_data['office_check']))
                                                           @else checked @endif>
                                                    <label for="office_check">Office</label>
                                                </div>

                                                <div class="col-md-3 col-sm-3 checkbox">
                                                    <input type="checkbox" id="shop_house_check" name="shop_house_check"
                                                           @if(isset($config_data) && isset($config_data['shop_house_check']))
                                                           {{ $config_data['shop_house_check'] }}
                                                           @elseif(isset($config_data) && !isset($config_data['shop_house_check']))
                                                           @else checked @endif>
                                                    <label for="shop_house_check">Shop House</label>
                                                </div>
                                                <div class="col-md-3 col-sm-3 checkbox">
                                                    <input type="checkbox" id="retail_check"
                                                           name="retail_check"
                                                           @if(isset($config_data) && isset($config_data['retail_check']))
                                                           {{ $config_data['retail_check'] }}
                                                           @elseif(isset($config_data) && !isset($config_data['retail_check']))
                                                           @else checked @endif>
                                                    <label for="retail_check">Retail</label>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="control-label col-sm-2 text-left label_item">3. Unit
                                                    size: </label>
                                                <div class="col-sm-2">
                                                    <select id="lower" name="lower" class="form-control input-sm">
                                                        <option value="-1" selected="selected">All Sizes</option>
                                                        <option value="0">0 sqft</option>
                                                        <option value="200">200 sqft</option>
                                                        <option value="300">300 sqft</option>
                                                        <option value="400">400 sqft</option>
                                                        <option value="500">500 sqft</option>
                                                        <option value="600">600 sqft</option>
                                                        <option value="700">700 sqft</option>
                                                        <option value="800">800 sqft</option>
                                                        <option value="900">900 sqft</option>
                                                        <option value="1000">1,000 sqft</option>
                                                        <option value="1100">1,100 sqft</option>
                                                        <option value="1200">1,200 sqft</option>
                                                        <option value="1300">1,300 sqft</option>
                                                        <option value="1400">1,400 sqft</option>
                                                        <option value="1500">1,500 sqft</option>
                                                        <option value="1600">1,600 sqft</option>
                                                        <option value="1700">1,700 sqft</option>
                                                        <option value="1800">1,800 sqft</option>
                                                        <option value="1900">1,900 sqft</option>
                                                        <option value="2000">2,000 sqft</option>
                                                        <option value="2100">2,100 sqft</option>
                                                        <option value="2200">2,200 sqft</option>
                                                        <option value="2300">2,300 sqft</option>
                                                        <option value="2400">2,400 sqft</option>
                                                        <option value="2500">2,500 sqft</option>
                                                        <option value="2600">2,600 sqft</option>
                                                        <option value="2700">2,700 sqft</option>
                                                        <option value="2800">2,800 sqft</option>
                                                        <option value="2900">2,900 sqft</option>
                                                        <option value="3000">3,000 sqft</option>
                                                    </select>
                                                </div>
                                                <label class="control-label col-sm-1 no-padding">to</label>
                                                <div class="col-sm-2">
                                                    <select id="upper" name="upper" class="form-control input-sm">
                                                        <option value="-1" selected="selected">All Sizes</option>
                                                        <option value="200">200 sqft</option>
                                                        <option value="300">300 sqft</option>
                                                        <option value="400">400 sqft</option>
                                                        <option value="500">500 sqft</option>
                                                        <option value="600">600 sqft</option>
                                                        <option value="700">700 sqft</option>
                                                        <option value="800">800 sqft</option>
                                                        <option value="900">900 sqft</option>
                                                        <option value="1000">1,000 sqft</option>
                                                        <option value="1100">1,100 sqft</option>
                                                        <option value="1200">1,200 sqft</option>
                                                        <option value="1300">1,300 sqft</option>
                                                        <option value="1400">1,400 sqft</option>
                                                        <option value="1500">1,500 sqft</option>
                                                        <option value="1600">1,600 sqft</option>
                                                        <option value="1700">1,700 sqft</option>
                                                        <option value="1800">1,800 sqft</option>
                                                        <option value="1900">1,900 sqft</option>
                                                        <option value="2000">2,000 sqft</option>
                                                        <option value="2100">2,100 sqft</option>
                                                        <option value="2200">2,200 sqft</option>
                                                        <option value="2300">2,300 sqft</option>
                                                        <option value="2400">2,400 sqft</option>
                                                        <option value="2500">2,500 sqft</option>
                                                        <option value="2600">2,600 sqft</option>
                                                        <option value="2700">2,700 sqft</option>
                                                        <option value="2800">2,800 sqft</option>
                                                        <option value="2900">2,900 sqft</option>
                                                        <option value="3000">3,000 sqft</option>
                                                        <option value="0">Unlimited</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-sm-2 text-left label_item">4.
                                                    STOREY: </label>
                                                <div class="col-sm-2">
                                                    <input type="number" id="storey_from" name="storey_from"
                                                           class="form-control input-sm" placeholder="">
                                                </div>
                                                <label class="control-label col-sm-1 no-padding">to </label>
                                                <div class="col-sm-2">
                                                    <input type="number" id="storey_to" name="storey_to"
                                                           class="form-control input-sm" placeholder="">
                                                </div>
                                                <i>For sales transactions only</i>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-sm-4 text-left label_item">5. STACKS
                                                    (SEPARATE BY COMMA): </label>
                                                <div class="col-sm-2">
                                                    <input type="text" id="stacks" name="stacks"
                                                           class="form-control input-sm" placeholder="">
                                                </div>
                                                <i>For sales transactions only</i>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label text-left label_item">6. INFORMATION TO
                                                    INCLUDE: </label>
                                            </div>
                                            <div class="form-group" style="padding: 0 30px;">
                                                <div class="col-md-3 col-sm-3 checkbox">
                                                    <input type="checkbox" id="developer_sales" name="developer_sales"
                                                           @if(isset($config_data) && isset($config_data['developer_sales']))
                                                           {{ $config_data['developer_sales'] }}
                                                           @elseif(isset($config_data) && !isset($config_data['developer_sales']))
                                                           @else checked @endif>
                                                    <label for="developer_sales">Developer Sales</label>
                                                </div>

                                                {{--<div class="col-md-3 col-sm-3 checkbox">--}}
                                                {{--<input type="checkbox" id="buyer_profile" name="buyer_profile"--}}
                                                {{--@if(isset($config_data) && isset($config_data['buyer_profile'])) {{ $config_data['buyer_profile'] }} @elseif(isset($config_data) && !isset($config_data['buyer_profile'])) @else checked @endif>--}}
                                                {{--<label for="buyer_profile">Buyer Profile</label>--}}
                                                {{--</div>--}}
                                                <div class="col-md-3 col-sm-3 checkbox">
                                                    <input type="checkbox" id="historical_prices_chart"
                                                           name="historical_prices_chart"
                                                           @if(isset($config_data) && isset($config_data['historical_prices_chart'])) {{ $config_data['historical_prices_chart'] }} @elseif(isset($config_data) && !isset($config_data['historical_prices_chart'])) @else checked @endif>
                                                    <label for="historical_prices_chart">Historical
                                                        Prices(Chart)</label>
                                                </div>
                                                <div class="col-md-3 col-sm-3 checkbox">
                                                    <input type="checkbox" id="historical_range_chart"
                                                           name="historical_range_chart"
                                                           @if(isset($config_data) && isset($config_data['historical_range_chart'])) {{ $config_data['historical_range_chart'] }} @elseif(isset($config_data) && !isset($config_data['historical_range_chart'])) @else checked @endif>
                                                    <label for="historical_range_chart">Historical Range(Chart)</label>
                                                </div>
                                                <div class="col-md-3 col-sm-3 checkbox">
                                                    <input type="checkbox" id="profitable_transactions"
                                                           name="profitable_transactions"
                                                           @if(isset($config_data) && isset($config_data['profitable_transactions'])) {{ $config_data['profitable_transactions'] }} @elseif(isset($config_data) && !isset($config_data['profitable_transactions'])) @else checked @endif>
                                                    <label for="profitable_transactions">Profitable Transactions</label>
                                                </div>
                                                <div class="col-md-3 col-sm-3 checkbox">
                                                    <input type="checkbox" id="unprofitable_transactions"
                                                           name="unprofitable_transactions"
                                                           @if(isset($config_data) && isset($config_data['unprofitable_transactions'])) {{ $config_data['unprofitable_transactions'] }} @elseif(isset($config_data) && !isset($config_data['unprofitable_transactions'])) @else checked @endif>
                                                    <label for="unprofitable_transactions">Unprofitable
                                                        Transactions</label>
                                                </div>

                                                <div class="col-md-3 col-sm-3 checkbox">
                                                    <input type="checkbox" id="rental_contracts" name="rental_contracts"
                                                           @if(isset($config_data) && isset($config_data['rental_contracts'])) {{ $config_data['rental_contracts'] }} @elseif(isset($config_data) && !isset($config_data['rental_contracts'])) @else checked @endif>
                                                    <label for="rental_contracts">Rental Contracts</label>
                                                </div>

                                                <div class="col-md-3 col-sm-3 checkbox">
                                                    <input type="checkbox" id="reportctrlj" name="reportctrlj"
                                                           @if(isset($config_data) && isset($config_data['reportctrlj'])) {{ $config_data['reportctrlj'] }} @elseif(isset($config_data) && !isset($config_data['reportctrlj'])) @else checked @endif>
                                                    <label for="reportctrlj">Rental Yield</label>
                                                </div>

                                                <div class="col-md-3 col-sm-3 checkbox">
                                                    <input type="checkbox" id="quanterly_rental" name="quanterly_rental"
                                                           @if(isset($config_data) && isset($config_data['quanterly_rental'])) {{ $config_data['quanterly_rental'] }} @elseif(isset($config_data) && !isset($config_data['quanterly_rental'])) @else checked @endif>
                                                    <label for="quanterly_rental">Quarterly Rental</label>
                                                </div>
                                                <div class="col-md-3 col-sm-3 checkbox">
                                                    <input type="checkbox" id="street_rental" name="street_rental"
                                                           @if(isset($config_data) && isset($config_data['street_rental'])) {{ $config_data['street_rental'] }} @elseif(isset($config_data) && !isset($config_data['street_rental'])) @else checked @endif>
                                                    <label for="street_rental">Street Rental</label>
                                                </div>
                                                <div class="col-md-3 col-sm-3 checkbox">
                                                    <input type="checkbox" id="nearby_comparison"
                                                           name="nearby_comparison"
                                                           @if(isset($config_data) && isset($config_data['nearby_comparison'])) {{ $config_data['nearby_comparison'] }} @elseif(isset($config_data) && !isset($config_data['nearby_comparison'])) @else checked @endif>
                                                    <label for="nearby_comparison">Nearby Comparison</label>
                                                </div>
                                                <div class="col-md-3 col-sm-3 checkbox">
                                                    <input type="checkbox" id="historical_transactions"
                                                           name="historical_transactions"
                                                           @if(isset($config_data) && isset($config_data['historical_transactions'])) {{ $config_data['historical_transactions'] }} @elseif(isset($config_data) && !isset($config_data['historical_transactions'])) @else checked @endif>
                                                    <label for="historical_transactions">Historical Transactions</label>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-sm-3 text-left label_item">7. HIDE UNIT
                                                    NUMBERS:</label>
                                                <div class="col-sm-2">
                                                    <select id="hide_unit_numbers" name="hide_unit_numbers"
                                                            class="form-control input-sm">
                                                        <option value="0" selected="selected">No</option>
                                                        <option value="3">Hide storey and stack</option>
                                                        <option value="1">Hide stack only</option>
                                                        <option value="2">Hide storey only</option>
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="control-label text-left label_item">
                                                    <button type="submit" class="btn btn-sm btn-primary"
                                                            id="refresh_report">REFRESH REPORT
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-success"
                                                            id="printer_friendly">
                                                        PRINTER FRIENDLY
                                                    </button>
                                                    <a type="button" class="btn btn-sm btn-danger"
                                                       id="print_to_pdf"
                                                       href="{{ url('/trends-and-analysis/residential/report/pdf?p=' . $project['Project Name']) }}">PRINTER
                                                        TO PDF
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-info"
                                                            id="save_report_settings">SAVE REPORT SETTINGS
                                                    </button>
                                                </label>
                                            </div>
                                        </form>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </div>
    <div class="divider"></div>
    {{-- End Project Configuration --}}

    <!-- Project Information -->
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
                                <td>{{ \App\Service\GlobalService::getStreetFromAddress($project['Address']) }}</td>
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
                                <td>{{ $project['Completion Date'] }}</td>
                            </tr>
                            <tr>
                                <td>NUMBER OF UNITS</td>
                                <td>{{ $project['No_of_Unit'] }} UNITS</td>
                            </tr>
                            <tr>
                                <td>INDICATIVE PRICE RANGE / AVERAGE*</td>
                                <td>
                                    @if(count($project_list_6_month) > 0)
                                        S${{ $project_list_6_month->min('Unit Price ($ psf)') }}
                                        -
                                        S$ {{ $project_list_6_month->max('Unit Price ($ psf)') }}
                                        PSF /
                                        S$ {{ round($project_list_6_month->average('Unit Price ($ psf)'), 2) }}
                                        PSF
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>INDICATIVE RENTAL RANGE / AVERAGE*</td>
                                <td>
                                    @if(count($residental_rental_6_month) > 0)
                                        S$ {{ $residental_rental_6_month->min('rental') }}
                                        -
                                        S$ {{ $residental_rental_6_month->max('rental') }}
                                        PSF PM /
                                        S$ {{ round($residental_rental_6_month->average('rental'), 2) }}
                                        PSF PM
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>IMPLIED RENTAL YIELD</td>
                                <td>
                                    @if(count($residental_rental_6_month) > 0 && count($project_list_6_month)>0)
                                        {{ round($residental_rental_6_month->average('rental')*12/$project_list_6_month->average('Unit Price ($ psf)')*100, 2) }}
                                        %
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @php
                                $historical_high_project = $project_list->where('Unit Price ($ psf)', $project_list->max('Unit Price ($ psf)'))->first();
                                $historical_low_project = $project_list->where('Unit Price ($ psf)', $project_list->min('Unit Price ($ psf)'))->first();
                            @endphp
                            <tr>
                                <td>HISTORICAL HIGH</td>
                                <td>
                                    @if($historical_high_project)
                                        S$ {{ $historical_high_project['Unit Price ($ psf)'] }} PSF
                                        IN {{ $historical_high_project['Contract Date'] }}
                                        FOR A {{ round($historical_high_project['Area (sqm)'] * 10.7639) }}-SQFT UNIT
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>INDICATIVE AVERAGE PRICE FROM HISTORICAL HIGH</td>
                                <td>@if($historical_high_project)
                                        {{ round(($project_list->average('Unit Price ($ psf)')-$historical_high_project['Unit Price ($ psf)'])/$historical_high_project['Unit Price ($ psf)'] * 100,2) }}
                                        %
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>HISTORICAL LOW</td>
                                <td>@if($historical_high_project)
                                        S$ {{ $historical_low_project['Unit Price ($ psf)'] }} PSF
                                        IN {{ $historical_low_project['Contract Date'] }}
                                        FOR A {{ round($historical_low_project['Area (sqm)'] * 10.7639) }}-SQFT UNIT
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <h6>Note: *Based on contracts in the last 6 months. #Based on all available caveats, it does not
                            represent the breakdown of current owners.</h6>
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
    <!-- End Project Information -->


    @if(\App\Service\GlobalService::checkUserPermission())
        <!-- Search by unit -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group form-inline">
                            <label>Unit Search</label>
                            <input class="form-control input-sm" id="units_for_address" placeholder="All Units">
                            <button class="btn btn-primary btn-sm" style="margin-left: -4px" id="btn_search_unit"><i
                                    class="fa fa-search"></i></button>
                        </div>
                        <i>Usage: '20-07', '20-' for storey 20, '-07' for stack 07</i>
                    </div>
                </div>
            </div>
        </div>
        <div class="divider"></div>
        <!-- End Search by unit -->

        <div class="slogan bottom-pad-small p-t-50 p-b-30 hidden" id="search_by_unit_panel">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h3 class="title" id="stack_stock_title"></h3>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="content-box">
                            <h4 class="text-center">ALL PAST TRANSACTIONS</h4>
                            <table class="table-striped table-bordered table minimalist" id="all_past_transaction">
                                <thead>
                                <tr>
                                    <th>Contract<br>date</th>
                                    <th>Address</th>
                                    <th>Unit area<br>(sqft)</th>
                                    <th>Price<br>(S$ psf)</th>
                                    <th>Price<br>(S$)</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="content-box">
                            <h4 class="text-center">RECENT TRANSACTIONS OF SIMILAR UNITS</h4>
                            <table class="table-striped table-bordered table minimalist" id="recent_transaction">
                                <thead>
                                <tr>
                                    <th>Contract<br>date</th>
                                    <th>Project</th>
                                    <th>Address</th>
                                    <th>Unit area<br>(sqft)</th>
                                    <th>Price<br>(S$ psf)</th>
                                    <th>Price<br>(S$)</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sales Start-->
        @if(!isset($config_data) || (isset($config_data) && isset($config_data['developer_sales'])))
            @php
                $story_list = $project_list;

            if (isset($config_data) && $config_data) {
                $story_from = $config_data['storey_from'];
                $story_to = $config_data['storey_to'];
                $stacks = $config_data['stacks'];
                $hide_unit_number = $config_data['hide_unit_numbers'];

                if ($story_from) {
                    $story_list = $story_list->filter(function ($item) use ($story_from) {
                       $story_address = $item['Address'];
                       if (count(explode('#', $story_address))>1) {
                            $story_stock = explode('#', $story_address)[1];
                            if (count(explode('-', $story_stock)) > 1) {
                                $story = trim(explode('-', $story_stock)[0]);
                                if ($story >= $story_from) {
                                    return true;
                                }
                            }
                       }
                    });
                }

                if ($story_to) {
                    $story_list = $story_list->filter(function ($item) use ($story_to) {
                       $story_address = $item['Address'];
                       if (count(explode('#', $story_address))>1) {
                            $story_stock = explode('#', $story_address)[1];
                            if (count(explode('-', $story_stock)) > 1) {
                                $story = trim(explode('-', $story_stock)[0]);
                                if ((int)$story <= $story_to) {
                                    return true;
                                }
                            }
                       }
                    });
                }

                if ($stacks) {
                    $story_list = $story_list->filter(function ($item) use ($stacks) {
                       $story_address = $item['Address'];
                       if (count(explode('#', $story_address))>1) {
                            $story_stock = explode('#', $story_address)[1];
                            if (count(explode('-', $story_stock)) > 1) {
                                $stack_filter_item = trim(explode('-', $story_stock)[1]);

                                $stack_list = explode(',', $stacks);
                                if (count($stack_list) > 0) {
                                    foreach ($stack_list as $stack_item) {
                                        if ((int)$stack_filter_item == (int)trim($stack_item)) {
                                            return true;
                                        }
                                    }
                                }
                            }
                       }
                    });
                }

                if ($hide_unit_number) {
                    $story_list = $story_list->map(function ($item) use ($hide_unit_number) {
                        $story_address = $item['Address'];

                        if (count(explode('#', $story_address))>1) {
                            $story_stock = explode('#', $story_address)[1];
                            if (count(explode('-', $story_stock)) > 1) {
                                $story = trim(explode('-', $story_stock)[0]);
                                $stack = trim(explode('-', $story_stock)[1]);
                            } else {
                                $story = trim(explode('-', $story_stock)[0]);
                                $stack = '';
                            }
                         } else {
                            $story = '';
                            $stack = '';
                         }

                        if ($hide_unit_number == 0) {
                        } elseif ($hide_unit_number == 1) {
                             $item['Address'] = explode('#', $story_address)[0] . '#' . $story . '-XX';
                        } elseif ($hide_unit_number == 2) {
                             $item['Address'] = explode('#', $story_address)[0] . '# XX-' . $stack;
                        } elseif ($hide_unit_number == 3) {
                             $item['Address'] = explode('#', $story_address)[0] . '# XX-XX';
                        }
                        return $item;
                    });
                }
            }

            @endphp
            <div class="slogan bottom-pad-small p-t-50 p-b-30">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h3 class="title"> Sales {{ $timeframe }}</h3>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="content-box">
                                {{--<h3 class="text-center">Location</h3>--}}
                                <table class="table-striped table-bordered table minimalist sales-datatable-component"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th>Contract<br>date</th>
                                        <th>Address</th>
                                        <th>Type of<br>sale</th>
                                        <th>Property Type</th>
                                        <th>Unit area<br>(sqft)</th>
                                        <th>Type of<br>area</th>
                                        <th>Price<br>(S$ psf)</th>
                                        <th>Price<br>(S$)</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($story_list as $project_item)
                                        <tr>
                                            <td>{{ $project_item['Contract Date'] }}</td>
                                            <td>{{ $project_item['Address'] }}</td>
                                            <td>{{ $project_item['Type of Sale'] }}</td>
                                            <td>{{ $project_item['Property Type'] }}</td>
                                            <td>{{ number_format(round($project_item['Area (sqm)'] * 10.76)) }}</td>
                                            <td>{{ $project_item['Type of Area'] }}</td>
                                            <td>{{ number_format($project_item['Unit Price ($ psf)']) }}</td>
                                            <td>{{ number_format($project_item['Transacted Price ($)']) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h4 class="text-center">HISTORICAL TRANSACTION PRICES</h4>
                            <div id="historical_transaction_chart_scatter" style="height: 500px" class="google-maps">

                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h4 class="text-center">HISTORICAL MONTHLY PRICE RANGE</h4>
                            <div id="historical_monthly_chart_range" style="height: 500px" class="google-maps">

                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="divider"></div>
        @endif
        <!-- Sales End-->

        {{-- Calculate Profit --}}
        @if(!isset($config_data) || ((isset($config_data) && isset($config_data['profitable_transactions'])) || (isset($config_data) && isset($config_data['unprofitable_transactions']))))
            @php
                $profit_result = \App\Service\NonResidentialService::getProfit($project_list);
                $profit_list = $profit_result['profit_list'];
                $unprofit_list = $profit_result['unprofit_list'];
            @endphp
            <div class="slogan bottom-pad-small p-t-50 p-b-30">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h3 class="title">Profitable and unprofitable transactions&nbsp;</h3>
                        </div>

                        @if(!isset($config_data) || (isset($config_data) && isset($config_data['profitable_transactions'])))
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h4 class="text-center">PROFITABLE TRANSACTIONS (TOTAL OF {{ count($profit_list) }}
                                    TRANSACTIONS)</h4>
                                <div class="content-box">
                                    @if(count($profit_list) > 0)
                                        <table
                                            class="table-striped table-bordered table minimalist profitable-datatable-component"
                                            width="100%"
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
                                    @else
                                        <p class="text-center">There is no data for this option.</p>
                                    @endif
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        @endif

                        @if(!isset($config_data) || (isset($config_data) && isset($config_data['unprofitable_transactions'])))
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h4 class="text-center">UNPROFITABLE TRANSACTIONS
                                    (TOTAL OF {{ count($unprofit_list) }} TRANSACTION)</h4>
                                <div class="content-box">
                                    {{--<h3 class="text-center">Location</h3>--}}
                                    @if(count($unprofit_list) > 0)
                                        <table
                                            class="table-striped table-bordered table minimalist profitable-datatable-component"
                                            width="100%"
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
                                    @else
                                        <p class="text-center">There is no data for this option.</p>
                                    @endif
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        @endif

                    </div>
                </div>
            </div>
            <div class="divider"></div>
        @endif
        {{-- End Calculate Profit --}}

        {{-- Get Historical Reantal --}}
        @php
            $historical_rental = \App\Service\NonResidentialService::getHistoricalRental($project['Address']);
        @endphp
        @if(!isset($config_data) || (isset($config_data) && isset($config_data['street_rental'])))
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
                                                <td>{{ $item['Month'] }}</td>
                                                <td>{{ $item['Street Name'] }}</td>
                                                <td>{{ $item['Type'] }}</td>
                                                <td>{{ $item['Minimum'] }}</td>
                                                <td>{{ $item['25th Percentile'] }}</td>
                                                <td>{{ $item['Median'] }}</td>
                                                <td>{{ $item['75th Percentile'] }}</td>
                                                <td>{{ $item['Maximum'] }}</td>
                                                <td>1</td>

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
        @endif
        {{-- End historical rental --}}

        {{-- Near By Properties--}}
        @if(!isset($config_data) || ((isset($config_data) && isset($config_data['nearby_comparison']))))
            <div class="divider"></div>
            <div class="slogan p-t-50 p-b-30">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h3 class="title"> NEARBY PROPERTIES &nbsp;</h3>
                        </div>


                        @if(!isset($config_data) || (isset($config_data) && isset($config_data['nearby_comparison'])))
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="content-box">
                                    <h4 class="text-center">PRICE AND RENTAL COMPARISONS (UP TO 50)</h4>
                                    <table class="table-striped table-bordered table minimalist datatable-component"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th>Marker</th>
                                            <th>Project</th>
                                            <th>Tenure</th>
                                            <th>Completion</th>
                                            <th>Distance <br>(m)</th>
                                            <th>Lowest price* <br>(S$ psf)</th>
                                            <th>Average price* <br>(S$ psf)</th>
                                            <th>Highest price* <br>(S$ psf)</th>
                                            <th>Lowest rental* <br>(S$ psf pm)</th>
                                            <th>Average rental* <br>(S$ psf pm)</th>
                                            <th>Highest rental* <br>(S$ psf pm)</th>
                                            <th>Rental yield <br>(%)</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td><img src="{{ asset('img/marker/marker0.png') }}"></td>
                                            <td>{{ $project['Project Name'] }}</td>
                                            <td>{{ $project['Tenure'] }}</td>
                                            <td>{{ $project['Completion Date'] }}</td>
                                            <td>-</td>
                                            <td>{{ round($residental_rental->min('Monthly Gross Rent($)'), 2) }}</td>
                                            <td>{{ round($residental_rental->average('Monthly Gross Rent($)'), 2) }}</td>
                                            <td>{{ round($residental_rental->max('Monthly Gross Rent($)'), 2) }}</td>
                                            <td>{{ round($residental_rental->min('rental'), 2) }}</td>
                                            <td>{{ round($residental_rental->average('rental'), 2) }}</td>
                                            <td>{{ round($residental_rental->max('rental'), 2) }}</td>
                                            @php
                                                if ($residental_rental->average('Monthly Gross Rent($)')) {
                                                    $rental_yield = round($residental_rental->average('rental')* 12 / $residental_rental->average('Monthly Gross Rent($)') * 100, 2);
                                                } else {
                                                    $rental_yield = null;
                                                }

                                                $marker_index = 0;
                                            @endphp
                                            <td>{{ $rental_yield }}</td>
                                        </tr>
                                        @foreach($nearby_items as $item)
                                            @if($item['Project Name'] != $project['Project Name'])
                                                @php
                                                    $nearby_projects_list = \App\Service\ResidentialService::getRentalData($item['Project Name']);
                                                    $nearby_projects_list = $nearby_projects_list->map(function($s_item) {
                                                        if ($s_item['Floor Area ll']) {
                                                         $s_item['rental'] = $s_item['Monthly Gross Rent($)']/$s_item['Floor Area ll'];
                                                        } else {
                                                         $s_item['rental'] = null;
                                                        }

                                                         return $s_item;
                                                    });

                                                if ($nearby_projects_list->average('Monthly Gross Rent($)')) {
                                                    $nearby_projects_list_rental_yield = round($nearby_projects_list->average('rental')* 12 / $nearby_projects_list->average('Monthly Gross Rent($)') * 100, 2);
                                                } else {
                                                    $nearby_projects_list_rental_yield = null;
                                                }
                                                $marker_index ++;
                                                $item['marker'] = "img/marker/marker" . $marker_index . ".png";
                                                @endphp
                                                <tr>
                                                    <td><img
                                                            src="{{ asset('img/marker/marker' . $marker_index . '.png') }}">
                                                    </td>
                                                    <td>{{ $item['Project Name'] }}</td>
                                                    <td>{{ $item['Tenure'] }}</td>
                                                    <td>{{ $item['Completion Date'] }}</td>
                                                    <td>{{ $item['distance'] }}</td>
                                                    <td>{{ round($nearby_projects_list->min('Monthly Gross Rent($)'), 2) }}</td>
                                                    <td>{{ round($nearby_projects_list->average('Monthly Gross Rent($)'), 2) }}</td>
                                                    <td>{{ round($nearby_projects_list->max('Monthly Gross Rent($)'), 2) }}</td>
                                                    <td>{{ round($nearby_projects_list->min('rental'), 2) }}</td>
                                                    <td>{{ round($nearby_projects_list->average('rental'), 2) }}</td>
                                                    <td>{{ round($nearby_projects_list->max('rental'), 2) }}</td>
                                                    <td>{{ $nearby_projects_list_rental_yield }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="google-maps" id="nearby_map"></div>
                            </div>
                    @endif
                    <!-- 3 Column Services End-->
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        @endif
        {{-- End Near by Properties--}}

    @endif
@endsection

@section('scripts')

    @include('TrendsAnalysis.footer_report_non_residential')
@endsection
