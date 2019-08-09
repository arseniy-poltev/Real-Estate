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



                </div>
            </div>
        </div>
    </div>
    <!-- Title, Breadcrumb End-->
    <!-- Main Content start-->
    <div class="content">
        <div class="container">
            <div class="row">
                @if (session('resent'))
                    <div class="alert alert-success" role="alert">
                        {{ __('A fresh verification link has been sent to your email address.') }}
                    </div>
                @endif
                    @if(Auth::guest())
                        <i>You can use this system for 24 hours for free. In order to browse premium content, click <a
                                href="{{ url('/checkout') }}" >here</a> to make a subscription.</i>
                    @elseif(Auth::User() && !Auth::User()->payment_verified)
                        <i>You can use this system for 24 hours for free. In order to browse premium content, click <a
                                href="{{ url('/checkout') }}" >here</a> to make a subscription.</i>
                    @elseif(Auth::User() && !Auth::User()->hasVerifiedEmail())
                        A fresh verification link has been sent to your email address.<br>
                        {{ __('Before proceeding, please check your email for a verification link.') }}
                        {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.
                    @elseif(Auth::User() && Auth::User()->payment_verified)
                        @php
                            $expire_date = \Carbon\Carbon::parse(Auth::User()->subscription_date)->addYears(1)->format('d M Y');
                        @endphp


                        @php
                            $subscription_expired = \Carbon\Carbon::now()->addYears(1);
                        @endphp

                        @if ($subscription_expired < \Carbon\Carbon::now())
                            Your account will be valid until <b>{{ $expire_date }}</b>.
                            In order to update your subscription, click <a
                                href="{{ url('/checkout') }}" >here.</a>
                        @else
                            Your account has been expired.
                            In order to update your subscription, click <a
                                href="{{ url('/checkout') }}" >here.</a>
                        @endif
                    @endif
            </div>
            <div class="row m-t-60">
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
