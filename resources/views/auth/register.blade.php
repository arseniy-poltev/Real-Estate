@extends('layouts.main')
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
    </style>
@endsection

@section('page_title', 'Register')
@section('contents')
    <!-- Title, Breadcrumb Start-->
    <div class="breadcrumb-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                    <h2 class="title">Register</h2>
                </div>
                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                    <div class="breadcrumbs pull-right">
                        <ul>
                            <li>You are here:</li>
                            <li>Register</li>
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
                <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12" id="contact-form">
                    <h3 class="title">Register</h3>
                    <p>
                        In order to search data, you have to register.
                    </p>
                    <p>
                        You can search data for 24 hours with free trial.
                    </p>
                    <div class="divider"></div>
                    <form method="POST" class="reply" action="{{ route('register') }}">
                        @csrf
                        <fieldset>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label for="name">{{ __('Name') }}: <span>*</span></label>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row m-t-20">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>{{ __('E-Mail Address') }}: <span>*</span></label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row m-t-20">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>{{ __('Password') }}: <span>*</span></label>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row m-t-20">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>{{ __('Confirm Password') }}: <span>*</span></label>
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>
                        </fieldset>
                        <button class="btn btn-normal btn-color submit  bottom-pad  m-t-20" type="submit">{{ __('Register') }}</button>
                        <div class="success alert-success alert" style="display:none">Your message has been sent successfully.</div>
                        <div class="error alert-error alert" style="display:none">E-mail must be valid and message must be longer than 100 characters.</div>
                        <div class="clearfix">
                        </div>
                    </form>
                </div>
                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                    <div class="address widget">
                        <h3 class="title">Head Office</h3>
                        <ul class="contact-us">
                            <li>
                                <i class="fa fa-map-marker"></i>
                                <p>
                                    <strong class="contact-pad">Address:</strong> House: 325, Road: 2,<br> Mirpur DOHS <br>
                                    Dhaka, Bangladesh
                                </p>
                            </li>
                            <li>
                                <i class="fa fa-phone"></i>
                                <p>
                                    <strong>Phone:</strong> +880 111-111-111
                                </p>
                            </li>
                            <li>
                                <i class="fa fa-envelope"></i>
                                <p>
                                    <strong>Email:</strong><a href="mailto:support@fifothemes.com">support@squarefoot.com</a>
                                </p>
                            </li>
                        </ul>
                    </div>
                    <div class="contact-info widget">
                        <h3 class="title">Business Hour</h3>
                        <ul class="business-hour">
                            <li><i class="fa fa-clock-o"> </i>Monday - Friday 9am to 5pm </li>
                            <li><i class="fa fa-clock-o"> </i>Saturday - 9am to 2pm</li>
                            <li><i class="fa fa-times-circle-o"> </i>Sunday - Closed</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Main Content end-->

@endsection
