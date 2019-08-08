<!DOCTYPE html>
<!--[if IE 8]>			<html class="ie ie8"> <![endif]-->
<!--[if IE 9]>			<html class="ie ie9"> <![endif]-->
<!--[if gt IE 9]><!-->	<html> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <title>@yield('page_title')</title>
    <meta name="description" content="Pixma Responsive HTML5/CSS3 Template from FIFOTHEMES.COM">
    <meta name="author" content="FIFOTHEMES.COM">
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Lato:400,300,400italic,700,300italic' rel='stylesheet' type='text/css'>

@include('layouts.styles')
@yield('styles')
<!-- Favicons -->
    <link rel="shortcut icon" href="{{ asset('img/ico/favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/ico/apple-touch-icon.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('img/ico/apple-touch-icon-72.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('img/ico/apple-touch-icon-114.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('img/ico/apple-touch-icon-144.png') }}">
    <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <script src="{{ asset('js/respond.min.js') }}"></script>
    <![endif]-->
    <!--[if IE]>
    <link rel="stylesheet" href="{{ asset('css/ie.css') }}">
    <![endif]-->
</head>
<body class="boxed home" style="overflow-y: auto; background-image: url({{ asset('/img/patterns/retina_wood.png') }});">
<div class="page-mask">
    <div class="page-loader">

        <div class="spinner"></div>
        Loading...
    </div>

</div>
<div class="wrap">
    <!-- Header Start -->
<!-- Header End -->
    <!-- Content Start -->
    <div id="main">
        @yield('contents')
    </div>
    <!-- Content End -->
    <!-- Footer Start -->
<!-- Scroll To Top -->
    <a href="#" class="scrollup"><i class="fa fa-angle-up"></i></a>
</div>
<!-- Wrap End -->
<section id="style-switcher">
    <h2>Style Switcher <a href="#"><i class="fa fa-cog"></i></a></h2>
    <div>
        <h3>Predefined Colors</h3>
        <ul class="colors">
            <li><a title="Blue" class="blue" href="#"></a></li>
            <li><a title="Green" class="green" href="#"></a></li>
            <li><a title="Orange" class="orange" href="#"></a></li>
            <li><a title="Purple" class="purple" href="#"></a></li>
            <li><a title="Red" class="red" href="#"></a></li>
            <li><a title="Magenta" class="magenta" href="#"></a></li>
            <li><a title="Brown" class="brown" href="#"></a></li>
            <li><a title="Gray" class="gray" href="#"></a></li>
            <li><a title="Golden" class="golden" href="#"></a></li>
            <li><a title="Teal" class="teal" href="#"></a></li>
        </ul>
        <h3>Layout Style</h3>
        <div class="layout-style">
            <select id="layout-style">
                <option value="1">Wide</option>
                <option value="2">Boxed</option>
            </select>
        </div>
        <h3>Header Color</h3>
        <div class="header-color">
            <input type='text' class="header-bg" />
        </div>
        <h3>Footer Top Color</h3>
        <div class="header-color">
            <input type='text' class="footer-bg" />
        </div>
        <h3>Footer Bottom Color</h3>
        <div class="header-color">
            <input type='text' class="footer-bottom" />
        </div>
        <h3>Background Image</h3>
        <ul id="bg" class="colors bg">
            <li><a class="bg1" href="#"></a></li>
            <li><a class="bg2" href="#"></a></li>
            <li><a class="bg3" href="#"></a></li>
            <li><a class="bg4" href="#"></a></li>
            <li><a class="bg5" href="#"></a></li>
            <li><a class="bg6" href="#"></a></li>
            <li><a class="bg7" href="#"></a></li>
            <li><a class="bg8" href="#"></a></li>
            <li><a class="bg9" href="#"></a></li>
            <li><a class="bg10" href="#"></a></li>
        </ul>
    </div>
</section>
<!-- The Scripts -->
@include('layouts.scripts')

@yield('scripts')
</body>
</html>
