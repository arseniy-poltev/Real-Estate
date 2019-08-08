<header id="header">
    <!-- Header Top Bar Start -->
    <div class="top-bar">
        <div class="slidedown collapse">
            <div class="container">
                <div class="phone-email pull-left">
                    <a><i class="fa fa-phone"></i> Call Us : +880 111-111-111</a>
                    <a><i class="fa fa-envelope"></i> Email : support@squarefoot.com</a>
                </div>

                <div class="follow-us pull-right">
                    <div class="pull-left mr-5" style="padding: 0 30px">
                        @guest
                            <span><a href="{{ url('login') }}">Login</a></span> |
                            <span><a href="{{ url('register') }}">Sign Up</a></span>
                        @else
                            <span class="dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                            </form>
                        </span>

                        @endguest
                    </div>

                    <div id="search-form" class="pull-right">
                        <form action="#" method="get">
                            <input type="text" class="search-text-box" placeholder="Search...">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header Top Bar End -->
    <!-- Main Header Start -->
    <div class="main-header">
        <div class="container">
            <!-- TopNav Start -->
            <div class="topnav navbar-header">
                <a class="navbar-toggle down-button" data-toggle="collapse" data-target=".slidedown">
                    <i class="fa fa-angle-down icon-current"></i>
                </a>
            </div>
            <!-- TopNav End -->
            <!-- Logo Start -->
            <div class="logo pull-left">
                <h1>
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('img/logo.png') }}" alt="pixma" width="185" height="60">
                    </a>
                </h1>
            </div>
            <!-- Logo End -->
            <!-- Mobile Menu Start -->
            <div class="mobile navbar-header">
                <a class="navbar-toggle" data-toggle="collapse" href=".navbar-collapse">
                    <i class="fa fa-bars fa-2x"></i>
                </a>
            </div>
            <!-- Mobile Menu End -->
            <!-- Menu Start -->
            <nav class="collapse navbar-collapse menu">
                <ul class="nav navbar-nav sf-menu">
                    <li>
                        <a href="{{ url('/') }}">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sf-with-ul">
                            Market Watch
                        </a>
                    </li>
                    @php
                        $current_url = \Illuminate\Support\Facades\Route::current()->getName();
                    @endphp
                    <li>
                        <a href="#"
                           @if($current_url == 'non-residential' || $current_url == 'residential' || $current_url == 'landed')
                           id="current"
                           @endif
                           class="sf-with-ul">
                            Trends & Analysis
                            <span class="sf-sub-indicator">
                            <i class="fa fa-angle-down "></i>
                        </span>
                        </a>
                        <ul>
                            <li><a href="{{ url('trends-and-analysis/residential') }}" class="sf-with-ul">Non-Landed
                                    Residential</a></li>
                            <li><a href="{{ url('trends-and-analysis/landed') }}" class="sf-with-ul">Landed
                                    Residential</a></li>
                            <li><a href="{{ url('trends-and-analysis/non-residential') }}" class="sf-with-ul">Commercial/Industrial</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" class="sf-with-ul">
                            Price Alert
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sf-with-ul">
                            Latest
                        </a>
                    </li>
                    <li><a href="#">
                            Publications
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- Menu End -->
        </div>
    </div>
    <!-- Main Header End -->
</header>
