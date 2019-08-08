@section('styles')
    <style>
        .custom-select.is-invalid, .form-control.is-invalid, .was-validated .custom-select:invalid, .was-validated .form-control:invalid {
            border-color: #e3342f;
        }

        .invalid-feedback {
            /*display: none;*/
            width: 100%;
            margin-top: .25rem;
            font-size: 80%;
            color: #e3342f;
        }

        .reply input {
            margin: 0;
        }

        .btn:hover {
            color: #23527c;
        }

        table.subscription td span.bullet {
            display: block;
            width: 100%;
            background: url('{{ url('/img/paypal/icon_yes.png') }}') no-repeat center;
        }

        table.subscription td span.nobullet {
            display: block;
            width: 100%;
            background: url('{{ url('/img/paypal/icon_no.png') }}') no-repeat center;
        }

        table.subscription tr td {
            font-size: 100%;
            text-align: center;
            text-transform: none;
            padding-top: 5px;
            padding-bottom: 5px;
        }

        table.subscription td.feature {
            text-align: left;
        }
    </style>
@endsection

@extends('layouts.main')

@section('page_title', 'Checkout')

@section('contents')
    <div class="breadcrumb-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <h2 class="title">Congratulations on visit to SquareFooter.</h2>
                </div>
                {{--<div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">--}}
                {{--<div class="breadcrumbs pull-right">--}}
                {{--<ul>--}}
                {{--<li>You are here:</li>--}}
                {{--<li><a>Home</a></li>--}}
                {{--</ul>--}}
                {{--</div>--}}
                {{--</div>--}}
            </div>
        </div>
    </div>
    <!-- Title, Breadcrumb End-->
    <!-- Title, Breadcrumb End-->
    <!-- Main Content start-->
    <div class="content">
        <div class="container">
            @if(\Illuminate\Support\Facades\Session::has('message'))
            <div class="row">
                <div class="col-md-12">
                    <div class="alert-{{ \Illuminate\Support\Facades\Session::get('code') }} alert">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        {{ \Illuminate\Support\Facades\Session::get('message') }}
                    </div>
                </div>
            </div>
                @php
                \Illuminate\Support\Facades\Session::forget('message');
                @endphp
            @endif
            <div class="row">
                <div class="posts-block col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <article>
                        <h3 class="title">Checkout for more information</h3>
                        <div class="post-content">
                            <p>
                                I AGREE THAT ALL INFORMATION FROM THIS WEBSITE IS MEANT FOR PERSONAL REFERENCE RESOURCE ONLY. I AGREE THAT THE INFORMATION IS NOT INTENDED TO BE AND DOES NOT CONSTITUTE FINANCIAL ADVICE, INVESTMENT ADVICE OR ANY OTHER ADVICE. I AGREE THAT I WILL NOT RELY ON ANY OF THE INFORMATION FOUND ON THIS WEBSITE AS AUTHORITATIVE OR SUBSTITUTE FOR THE EXERCISE OF MY OWN SKILL AND JUDGMENT IN MAKING ANY INVESTMENT OR OTHER DECISION. I AGREE THAT ANY AND ALL USE OF THE INFORMATION WHICH I MAKE, IS SOLELY AT MY OWN RISK AND WITHOUT ANY RECOURSE WHATSOEVER TO SQUARE FOOT RESEARCH PTE LTD, ITS RELATED AND AFFILIATE COMPANIES AND/OR THEIR EMPLOYEES. SQUARE FOOT RESEARCH PTE LTD MAKES REASONABLE EFFORT TO USE RELIABLE AND COMPREHENSIVE INFORMATION BUT WE MAKE NO REPRESENTATION THAT IT IS ACCURATE OR COMPLETE. I UNDERSTAND AND AGREE THAT ALL FEES PAID ARE FINAL AND NON-REFUNDABLE.
                            </p>
                        </div>
                    </article>
                </div>
                <!-- Left Section End -->
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <a href="{{ url('/checkout/pay-with-paypal') }}" target="_blank"><img src="{{ asset('img/paypal/buynowcc-blue2.png') }}"></a>
                </div>
            </div>
            <div class="divider"></div>
            <!-- 2 Column Testimonials -->
            <div class="row slogan bottom-pad-small p-t-50 p-b-30">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="testimonial item">
                        <table width="100%" class="subscription table">
                            <tbody><tr class="header">
                                <td width="18%"></td>
                                <td width="18%">Ordinary<br>Registered Users</td>
                                <td width="18%">Paid<br>Subscribers</td>
                                <td width="46%"></td>
                            </tr>
                            <tr class="alt">
                                <td>Unit Information#</td>
                                <td class="description"><span class="nobullet">&nbsp;</span></td>
                                <td class="description"><span class="bullet">&nbsp;</span></td>
                                <td class="feature">Every unit is different, see the most valuable bit of information</td>
                            </tr>
                            <tr>
                                <td>Unit Search</td>
                                <td class="description"><span class="nobullet">&nbsp;</span></td>
                                <td class="description"><span class="bullet">&nbsp;</span></td>
                                <td class="feature">Locate relevant transactions quickly</td>
                            </tr>
                            <tr class="alt">
                                <td>Past Transactions*</td>
                                <td class="description">(Limited to 15)</td>
                                <td class="description"><span class="bullet">&nbsp;</span></td>
                                <td class="feature">History matters, see transactions as far back as 1995</td>
                            </tr>
                            <tr>
                                <td>Profitability Analysis</td>
                                <td class="description">(Limited to 5)</td>
                                <td class="description"><span class="bullet">&nbsp;</span></td>
                                <td class="feature">See what are sellers making in today's market!</td>
                            </tr>
                            <tr class="alt">
                                <td>PDF Generation</td>
                                <td class="description"><span class="nobullet">&nbsp;</span></td>
                                <td class="description"><span class="bullet">&nbsp;</span></td>
                                <td class="feature">Like our reports? Get it in PDF format with one click</td>
                            </tr>
                            <tr>
                                <td>Report Customisation</td>
                                <td class="description"><span class="nobullet">&nbsp;</span></td>
                                <td class="description"><span class="bullet">&nbsp;</span></td>
                                <td class="feature">Don't like what you see? Feel free to slice and dice the report</td>
                            </tr>
                            <tr class="alt">
                                <td>Tower View</td>
                                <td class="description"><span class="nobullet">&nbsp;</span></td>
                                <td class="description"><span class="bullet">&nbsp;</span></td>
                                <td class="feature">View prices arranged in a grid, first of its kind!</td>
                            </tr>
                            <tr>
                                <td>Street View</td>
                                <td class="description"><span class="nobullet">&nbsp;</span></td>
                                <td class="description"><span class="bullet">&nbsp;</span></td>
                                <td class="feature">View transactions as if you are traveling along a street!</td>
                            </tr>
                            <tr class="alt">
                                <td>District View</td>
                                <td class="description"><span class="nobullet">&nbsp;</span></td>
                                <td class="description"><span class="bullet">&nbsp;</span></td>
                                <td class="feature">See projects ranked by capital gain, rental yield and more!</td>
                            </tr>
                            <tr>
                                <td>Location Scan</td>
                                <td class="description"><span class="nobullet">&nbsp;</span></td>
                                <td class="description"><span class="bullet">&nbsp;</span></td>
                                <td class="feature">Instantly have a bird's eye view on all projects in a location</td>
                            </tr>
                            <tr class="alt">
                                <td>Compare Projects</td>
                                <td class="description"><span class="nobullet">&nbsp;</span></td>
                                <td class="description"><span class="bullet">&nbsp;</span></td>
                                <td class="feature">Build a comparison portfolio in clicks!</td>
                            </tr>
                            <tr>
                                <td>Shortlist Projects</td>
                                <td class="description"><span class="nobullet">&nbsp;</span></td>
                                <td class="description"><span class="bullet">&nbsp;</span></td>
                                <td class="feature">Find projects matching your own price or location criteria</td>
                            </tr>
                            <tr class="alt">
                                <td>Amenities Check</td>
                                <td class="description"><span class="nobullet">&nbsp;</span></td>
                                <td class="description"><span class="bullet">&nbsp;</span></td>
                                <td class="feature">Quickly identify important amenities around any location</td>
                            </tr>
                            <tr>
                                <td>Technical Analysis</td>
                                <td class="description"><span class="nobullet">&nbsp;</span></td>
                                <td class="description"><span class="bullet">&nbsp;</span></td>
                                <td class="feature">Plot trend lines and other technical indicators</td>
                            </tr>
                            <tr class="alt">
                                <td>Government Land Sales</td>
                                <td class="description"><span class="nobullet">&nbsp;</span></td>
                                <td class="description"><span class="bullet">&nbsp;</span></td>
                                <td class="feature">Stay ahead of the curve! Be the first to work out developer's selling price!</td>
                            </tr>
                            </tbody></table>
                        <div class="testimonials-arrow">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Content end-->


@endsection
