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

    </style>

    <link href="{{ asset('plugin/icheck/skins/all.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
@endsection

@section('page_title', 'REPORT | COMMERCIAL / INDUSTRIAL')

@section('contents')

    @php
        $project_list = \App\Models\CommonTransaction::getTransactionProjectList($project['Project Name']);

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
                                        <div class="form-group">
                                            <label class="control-label col-sm-2 text-left label_item">1. TIME
                                                PERIOD:</label>
                                            <div class="col-sm-2">
                                                <select id="timeframe" class="form-control input-sm">
                                                    <option value="" selected="">All data</option>
                                                    <option value="10">Last 10 years</option>
                                                    <option value="9">Last 9 years</option>
                                                    <option value="8">Last 8 years</option>
                                                    <option value="7">Last 7 years</option>
                                                    <option value="6">Last 6 years</option>
                                                    <option value="5">Last 5 years</option>
                                                    <option value="4">Last 4 years</option>
                                                    <option value="3">Last 3 years</option>
                                                    <option value="2">Last 2 years</option>
                                                    <option value="1">Last 1 year</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2 text-left label_item">2. Unit
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
                                            <label class="control-label col-sm-2 text-left label_item">3.
                                                STOREY: </label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control input-sm" placeholder="">
                                            </div>
                                            <label class="control-label col-sm-1 no-padding">to </label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control input-sm" placeholder="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-sm-4 text-left label_item">4. STACKS
                                                (SEPARATE BY COMMA): </label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control input-sm" placeholder="">
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label class="control-label text-left label_item">5. INFORMATION TO
                                                INCLUDE: </label>
                                        </div>
                                        <div class="form-group" style="padding: 0 30px;">
                                            <div class="col-md-3 col-sm-3 checkbox">
                                                <input tabindex="14" type="checkbox" id="flat-checkbox-2" checked>
                                                <label for="flat-checkbox-2">Asking Prices</label>
                                            </div>
                                            <div class="col-md-3 col-sm-3 checkbox">
                                                <input tabindex="14" type="checkbox" id="flat-checkbox-3" checked>
                                                <label for="flat-checkbox-3">Asking Rents</label>
                                            </div>
                                            <div class="col-md-3 col-sm-3 checkbox">
                                                <input tabindex="14" type="checkbox" id="flat-checkbox-4" checked>
                                                <label for="flat-checkbox-4">Developer Sales</label>
                                            </div>
                                            <div class="col-md-3 col-sm-3 checkbox">
                                                <input tabindex="14" type="checkbox" id="flat-checkbox-5" checked>
                                                <label for="flat-checkbox-5">Developer Prices</label>
                                            </div>
                                            <div class="col-md-3 col-sm-3 checkbox">
                                                <input tabindex="14" type="checkbox" id="flat-checkbox-6" checked>
                                                <label for="flat-checkbox-6">Recent Sales</label>
                                            </div>
                                            <div class="col-md-3 col-sm-3 checkbox">
                                                <input tabindex="14" type="checkbox" id="flat-checkbox-7" checked>
                                                <label for="flat-checkbox-7">Buyer Profile</label>
                                            </div>
                                            <div class="col-md-3 col-sm-3 checkbox">
                                                <input tabindex="14" type="checkbox" id="flat-checkbox-8" checked>
                                                <label for="flat-checkbox-8">Historical Prices(Chart)</label>
                                            </div>
                                            <div class="col-md-3 col-sm-3 checkbox">
                                                <input tabindex="14" type="checkbox" id="flat-checkbox-9" checked>
                                                <label for="flat-checkbox-9">Historical Range(Chart)</label>
                                            </div>
                                            <div class="col-md-3 col-sm-3 checkbox">
                                                <input tabindex="14" type="checkbox" id="flat-checkbox-10" checked>
                                                <label for="flat-checkbox-10">Profitable Transactions</label>
                                            </div>
                                            <div class="col-md-3 col-sm-3 checkbox">
                                                <input tabindex="14" type="checkbox" id="flat-checkbox-11" checked>
                                                <label for="flat-checkbox-11">Unprofitable Transactions</label>
                                            </div>
                                            <div class="col-md-3 col-sm-3 checkbox">
                                                <input tabindex="14" type="checkbox" id="flat-checkbox-12" checked>
                                                <label for="flat-checkbox-12">Bulk Purchases</label>
                                            </div>
                                            <div class="col-md-3 col-sm-3 checkbox">
                                                <input tabindex="14" type="checkbox" id="flat-checkbox-13" checked>
                                                <label for="flat-checkbox-13">Rental Contracts</label>
                                            </div>
                                            <div class="col-md-3 col-sm-3 checkbox">
                                                <input tabindex="14" type="checkbox" id="flat-checkbox-14" checked>
                                                <label for="flat-checkbox-14">Rental Yield</label>
                                            </div>
                                            <div class="col-md-3 col-sm-3 checkbox">
                                                <input tabindex="14" type="checkbox" id="flat-checkbox-15" checked>
                                                <label for="flat-checkbox-15">Quarterly Rental</label>
                                            </div>
                                            <div class="col-md-3 col-sm-3 checkbox">
                                                <input tabindex="14" type="checkbox" id="flat-checkbox-16" checked>
                                                <label for="flat-checkbox-16">Street Rental</label>
                                            </div>
                                            <div class="col-md-3 col-sm-3 checkbox">
                                                <input tabindex="14" type="checkbox" id="flat-checkbox-17" checked>
                                                <label for="flat-checkbox-17">Unit Size Distribution</label>
                                            </div>
                                            <div class="col-md-3 col-sm-3 checkbox">
                                                <input tabindex="14" type="checkbox" id="flat-checkbox-18" checked>
                                                <label for="flat-checkbox-18">Nearby Prices (Chart)</label>
                                            </div>
                                            <div class="col-md-3 col-sm-3 checkbox">
                                                <input tabindex="14" type="checkbox" id="flat-checkbox-19" checked>
                                                <label for="flat-checkbox-19">Nearby Rental (Chart)</label>
                                            </div>
                                            <div class="col-md-3 col-sm-3 checkbox">
                                                <input tabindex="14" type="checkbox" id="flat-checkbox-20" checked>
                                                <label for="flat-checkbox-20">Nearby Comparison</label>
                                            </div>
                                            <div class="col-md-3 col-sm-3 checkbox">
                                                <input tabindex="14" type="checkbox" id="flat-checkbox-21" checked>
                                                <label for="flat-checkbox-21">Nearby Land Sales</label>
                                            </div>
                                            <div class="col-md-3 col-sm-3 checkbox">
                                                <input tabindex="14" type="checkbox" id="flat-checkbox-22" checked>
                                                <label for="flat-checkbox-22">Historical Transactions</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-sm-3 text-left label_item">6. HIDE UNIT
                                                NUMBERS:</label>
                                            <div class="col-sm-2">
                                                <select id="mask" name="mask" class="form-control input-sm">
                                                    <option value="0" selected="selected">No</option>
                                                    <option value="3">Hide storey and stack</option>
                                                    <option value="1">Hide stack only</option>
                                                    <option value="2">Hide storey only</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-sm-3 text-left label_item">7. ANONYMISE
                                                LISTINGS:</label>
                                            <div class="col-sm-2">
                                                <select id="anony" name="anony" class="form-control input-sm">
                                                    <option value="0" selected="selected">No</option>
                                                    <option value="1">Yes</option>
                                                </select>
                                            </div>
                                        </div>
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
                                        S$ {{ $project_list->min('Unit Price ($ psf)') }}
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

                            {{--@foreach(\App\Models\CommonTransaction::getTransactionProjectList($project['Project Name']) as $project_item)--}}
                                {{--<tr>--}}
                                    {{--<td>{{ $project_item['Contract Date'] }}</td>--}}
                                    {{--<td>{{ $project_item['Address'] }}</td>--}}
                                    {{--<td>{{ $project_item['Type of Sale'] }}</td>--}}
                                    {{--<td>{{ $project_item['Area (sqm)'] * 10.76 }}</td>--}}
                                    {{--<td>{{ $project_item['Type of Area'] }}</td>--}}
                                    {{--<td>{{ $project_item['Unit Price ($ psf)'] }}</td>--}}
                                    {{--<td>{{ $project_item['Transacted Price ($)'] }}</td>--}}
                                    {{--<td>{{ $project_item['Purchaser Address'] }}</td>--}}
                                {{--</tr>--}}
                            {{--@endforeach--}}
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <!-- Slogan End-->




    {{-- Calculate Profit --}}
    @php
        $profit_result = \App\Service\CommercialService::getProfit($project['Project Name']);
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
                    <h4 class="text-center">PROFITABLE TRANSACTIONS (TOTAL OF {{ count($profit_list) }} TRANSACTIONS)</h4>
                    <div class="content-box">
                        @if(count($profit_list) > 0)
                        <table class="table-striped table-bordered table minimalist " width="100%" id="atidph-table">
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
                        <table class="table-striped table-bordered table minimalist " width="100%" id="atidph-table">
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


    <div class="divider"></div>
    <div class="services-big">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h3 class="title">Rental </h3>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h4 class="text-center">HISTORICAL RENTAL ALONG {{ \App\Service\GlobalService::getStreetFromAddress($project['Address']) }}</h4>
                    @php
                        $rental_list = \App\Service\CommercialService::getHistoricalRental($project['Address']);
                    @endphp
                    <div class="content-box">
                        {{--<h3 class="text-center">Location</h3>--}}
                        <table class="table-striped table-bordered table minimalist " width="100%" id="atidph-table">
                            <thead>
                            <tr>
                                <th>Month</th>
                                <th>Type</th>
                                <th>Lowest Rental<br>(S$ psf pm)</th>
                                <th>Rental 25th<br>(S$ psf pm)</th>
                                <th>Median Rental<br>(S$ psf pm)</th>
                                <th>Rental 75th<br>(S$ psf pm)</th>
                                <th>Highest Rental<br>(S$ psf pm)</th>
                                <th>Contracts</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($rental_list as $item)
                            <tr>
                                <td>Month</td>
                                <td>{{ $item['Type'] }}</td>
                                <td>{{ $item['Minimum'] }}</td>
                                <td>{{ $item['25th Percentile'] }}</td>
                                <td>{{ $item['Median'] }}</td>
                                <td>{{ $item['75th Percentile'] }}</td>
                                <td>{{ $item['Maximum in $ psf per month'] }}</td>
                                <td>Contracts</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="divider"></div>
    <div class="slogan bottom-pad-small p-t-50 p-b-30">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h3 class="title"> Nearby Properties &nbsp;</h3>
                </div>
                    @php
                        $nearby_items = \App\Service\CommercialService::getNearByProperties($project['Address']);
                    @endphp

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="content-box">
                        <h3 class="text-center">HISTORICAL QUARTERLY RENTAL</h3>
                        <table class="table-striped table-bordered table minimalist " width="100%" id="atidph-table">
                            <thead>
                            <tr>
                                <th>Marker</th>
                                <th>Project</th>
                                <th>Type</th>
                                <th>Tenure</th>
                                <th>Distance <br>(m)</th>
                                <th>Lowest<br>price* <br>(S$ psf)</th>
                                <th>Average<br>price* <br>(S$ psf)</th>
                                <th>Highest<br>price* <br>(S$ psf)</th>
                                <th>Rental<br>25th* <br>(S$ psf pm)</th>
                                <th>Median<br>Rental* <br>(S$ psf pm)</th>
                                <th>Rental<br>75th* <br>(S$ psf pm)</th>
                                <th>Rental yield <br>(%)</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($nearby_items as $item)
                                <tr>
                                    <td>Marker</td>
                                    <td>{{ $item['Project Name'] }}</td>
                                    <td>{{ $item['Minimum'] }}</td>
                                    <td>{{ $item['25th Percentile'] }}</td>
                                    <td>{{ $item['Median'] }}</td>
                                    <td>{{ $item['75th Percentile'] }}</td>
                                    <td>{{ $item['Maximum in $ psf per month'] }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endforeach
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
    <div class="service-reasons">
        <div class="bg-overlay">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h3 class="title">Three Reasons to Work with Us</h3>
                    </div>
                    <div class="divider"></div>
                    <div class="reasons">
                        <div class="col-lg-4 col-md-4 col-sm-4 animate_afc d1">
                            <div class="content-box big ch-item bottom-pad-small">
                                <div class="ch-info-wrap">
                                    <div class="ch-info">
                                        <div class="ch-info-front ch-img-1"><i class="fa fa-rocket"></i></div>
                                        <div class="ch-info-back">
                                            <i class="fa fa-rocket"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="content-box-info">
                                    <h3>Professional Quality</h3>
                                    <p>
                                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                        Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                        unknown printer took a galley of type and scrambled it to make a type specimen
                                        book.
                                    </p>
                                    <a href="#">Read More <i class="fa fa-angle-right"></i><i
                                            class="fa fa-angle-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 animate_afc d2">
                            <div class="content-box big ch-item bottom-pad-small">
                                <div class="ch-info-wrap">
                                    <div class="ch-info">
                                        <div class="ch-info-front ch-img-1"><i class="fa fa-code"></i></div>
                                        <div class="ch-info-back">
                                            <i class="fa fa-code"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="content-box-info">
                                    <h3>Perfect Implementation</h3>
                                    <p>
                                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                        Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                        unknown printer took a galley of type and scrambled it to make a type specimen
                                        book.
                                    </p>
                                    <a href="#">Read More <i class="fa fa-angle-right"></i><i
                                            class="fa fa-angle-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 animate_afc d3">
                            <div class="content-box big ch-item">
                                <div class="ch-info-wrap">
                                    <div class="ch-info">
                                        <div class="ch-info-front ch-img-1"><i class="fa fa-check"></i></div>
                                        <div class="ch-info-back">
                                            <i class="fa fa-check"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="content-box-info">
                                    <h3>On Time Delivery</h3>
                                    <p>
                                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                        Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                        unknown printer took a galley of type and scrambled it to make a type specimen
                                        book.
                                    </p>
                                    <a href="#">Read More <i class="fa fa-angle-right"></i><i
                                            class="fa fa-angle-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Content end-->

@endsection

@section('scripts')

    <script>
        function initMap() {
            var map = new google.maps.Map(document.getElementById('maps'), {
                zoom: 8,
                center: {lat: -34.397, lng: 150.644}
            });
            var geocoder = new google.maps.Geocoder();

            document.getElementById('submit').addEventListener('click', function() {
                geocodeAddress(geocoder, map);
            });
        }

        function geocodeAddress(geocoder, resultsMap) {
            var address = document.getElementById('address').value;
            geocoder.geocode({'address': address}, function(results, status) {
                if (status === 'OK') {
                    resultsMap.setCenter(results[0].geometry.location);
                    var marker = new google.maps.Marker({
                        map: resultsMap,
                        position: results[0].geometry.location
                    });
                } else {
                    alert('Geocode was not successful for the following reason: ' + status);
                }
            });
        }
    </script>
    <script src="{{ asset('plugin/icheck/icheck.js') }}"></script>
    <script src="{{ asset('js/jquery.gmap.min.js') }}"></script>
    <script src="http://maps.google.com/maps/api/js?sensor=false&key=AIzaSyDi17-WDd-SPlZ_XtX6uioOeNS7-PEGFoc&callback&callback=initMap"></script>
    {{--<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCAY14CXuA_8UTgq6ipb-4Rm4C0jeCiHpY&callback=initMap"--}}
            {{--type="text/javascript"></script>--}}
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="https://www.amcharts.com/lib/4/core.js"></script>
    <script src="https://www.amcharts.com/lib/4/charts.js"></script>
    <script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>

    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>


    <script>
        $(document).ready(function () {
            // codeAddress();
            $('.checkbox input').iCheck({
                checkboxClass: 'icheckbox_flat-blue',
                radioClass: 'iradio_flat-blue'
            });
        })
    </script>

    <script>
            am4core.ready(function () {

                // Themes begin
                am4core.useTheme(am4themes_animated);
                // Themes end

                // Create chart instance
                var chart = am4core.create("buyer_profile_chart_div", am4charts.PieChart);

                // Set data
                var selected;
                var types = [{
                    type: "Fossil Energy",
                    percent: 70,
                    color: chart.colors.getIndex(0),
                    subs: [{
                        type: "Oil",
                        percent: 15
                    }, {
                        type: "Coal",
                        percent: 35
                    }, {
                        type: "Nuclear",
                        percent: 20
                    }]
                }, {
                    type: "Green Energy",
                    percent: 30,
                    color: chart.colors.getIndex(5),
                    subs: [{
                        type: "Hydro",
                        percent: 15
                    }, {
                        type: "Wind",
                        percent: 10
                    }, {
                        type: "Other",
                        percent: 5
                    }]
                }];

// Add data
                chart.data = generateChartData();

// Add and configure Series
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

            }); // end am4core.ready()

    </script>
@endsection
