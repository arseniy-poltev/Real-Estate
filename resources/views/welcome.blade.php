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


    </style>
@endsection

@extends('layouts.main')

@section('page_title', 'Home')

@section('contents')

    <div class="breadcrumb-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <h2 class="title">Congratulations on visit to SquareFooter.</h2>
                    @if(Auth::guest() || (Auth::User() && !Auth::User()->payment_verified))
                    <i>You can use this system for 24 hours for free. In order to browse premium content, click <a
                            href="{{ url('/checkout') }}" >here</a> to make a subscription.</i>
                    @endif
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
    <!-- Main Content start-->
    <div class="content">
        <div class="container">

            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <img class="text-center"
                         src="{{ asset('img/sgdmap.gif')}}">
                </div>
                <div class="col-md-3"></div>

            </div>
        </div>
    </div>

@endsection
