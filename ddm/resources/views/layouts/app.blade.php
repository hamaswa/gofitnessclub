<!DOCTYPE html>

<html class="h-100">



<head>

    <!-- Required meta tags -->

    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">

    <meta NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">

    <!-- Fontawesome CSS -->
    <link rel="icon" href="{{ url('css/favicon.png') }}">


    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <title>@yield('title')</title>

</head>



<body class="bg-light">

    <!-- Header -->

    <nav class="navbar navbar-dark bg-dark">

        <div class="container d-flex justify-content-center">

            <a class="navbar-brand" href="{{ route('root') }}">goFitness</a>

        </div>

    </nav>

    <!-- Container -->

    <div class="container wrapper" id="content">

        @yield('content')

    </div>

    <!-- Footer -->

    <nav class="navbar navbar-expand-sm navbar-dark bg-dark fixed-bottom">

        <div class="container">

            <a class="navbar-brand" href="#">Navbar</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">

                <span class="navbar-toggler-icon"></span>

            </button>

            <div class="collapse navbar-collapse" id="navbarNavDropdown">

                <ul class="navbar-nav">

                    <li class="nav-item">

                        <a class="nav-link nav-menu-link active" aria-current="page" href=""
                            data-href="{{ url('/home') }}">Dining</a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link nav-menu-link" aria-current="page" href=""
                            data-href="{{ route('buying-index') }}">Buying</a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link nav-menu-link" href="" data-href="{{ url('/library') }}">Library</a>

                    </li>

                    <li class="nav-item dropdown dropup">

                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" href="#"
                            role="button">History</a>

                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item nav-menu-link" href="#"
                                    data-href="{{ url('/show_monthly_dite') }}">Dining</a></li>
                            <li><a class="dropdown-item nav-menu-link" href="#"
                                    data-href="{{ route("show_monthly_buying") }}">Buying</a></li>

                        </ul>
                    </li>
                    <li class="nav-item dropdown dropup">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" href="#"
                            role="button">Settings</a>

                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item nav-menu-link" href="#"
                                    data-href="{{ route('get_shop_list') }}">
                                    Shop List</a></li>
                            <li><a class="dropdown-item nav-menu-link" href="#"
                                    data-href="{{ route('get_brand_list') }}">
                                    Brand List</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">

                        <a class="nav-link nav-menu-link" href=""
                            data-href="{{ url('/show_monthly_dite_report') }}">Report</a>

                    </li>

                </ul>

            </div>

        </div>

    </nav>



    @yield('modal')



    <!-- Scrips -->

    <script src="{{ asset('js/jquery-3.6.0.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/bootstrap.bundle.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/script.js') }}" type="text/javascript"></script>

</body>

</html>
