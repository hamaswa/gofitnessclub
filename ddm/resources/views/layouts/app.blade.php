<!DOCTYPE html>

<html class="h-100">



<head>

    <!-- Required meta tags -->

    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">

    <meta NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">

    <!-- Fontawesome CSS -->
    <link rel="icon" href="{{ url('css/favicon.png') }}">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
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

    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-bottom px-3 py-2">

            <a class="navbar-brand" href="javascript:void(0)">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarDropdown" aria-controls="navbarDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarDropdown">
                <ul class="navbar-nav">

                    <li class="nav-item">

                        <a class="nav-link nav-menu-link active" aria-current="page" href=""
                           data-href="{{ route('home') }}">Dining</a>

                    </li>

                    <li class="nav-item">

                        <a data-bs-toggle="collapse" data-bs-target=".navbar-collapse.show" class="nav-link nav-menu-link" aria-current="page" href=""
                           data-href="{{ route('buying-index') }}">Buying</a>

                    </li>

                    <li class="nav-item">

                        <a data-bs-toggle="collapse" data-bs-target=".navbar-collapse.show"
                           class="nav-link nav-menu-link" href="" data-href="{{ route('library') }}">Library</a>

                    </li>

                    <li class="nav-item dropdown dropup">

                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"
                           href="javascript:void(0)" role="button">History</a>

                        <ul class="dropdown-menu">
                            <li><a data-bs-toggle="collapse" data-bs-target=".navbar-collapse.show" class="dropdown-item nav-menu-link" href="javascript:void(0)"
                                   data-href="{{ route('monthly_dite') }}">Dining</a></li>
                            <li><a data-bs-toggle="collapse" data-bs-target=".navbar-collapse.show" class="dropdown-item nav-menu-link" href="javascript:void(0)"
                                   data-href="{{ route("show_monthly_buying") }}">Buying</a></li>

                        </ul>
                    </li>
                    <li class="nav-item dropdown dropup">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" href="javascript:void(0)"
                           role="button">Settings</a>

                        <ul class="dropdown-menu">
                            <li><a data-bs-toggle="collapse" data-bs-target=".navbar-collapse.show" class="dropdown-item nav-menu-link" href="javascript:void(0)"
                                   data-href="{{ route('get_shop_list') }}">
                                    Shop List</a></li>
                            <li><a data-bs-toggle="collapse" data-bs-target=".navbar-collapse.show" class="dropdown-item nav-menu-link" href="javascript:void(0)"
                                   data-href="{{ route('get_brand_list') }}">
                                    Brand List</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">

                        <a data-bs-toggle="collapse" data-bs-target=".navbar-collapse.show" class="nav-link nav-menu-link" href=""
                           data-href="{{ route('monthly_dite_report') }}">Report</a>

                    </li>

                </ul>
            </div>
        </nav>
    </div>

    @yield('modal')



    <!-- Scrips -->

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" type="text/javascript"></script>
    <script src="{{ asset('js/jquery-3.6.0.js') }}" type="text/javascript"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
        <script src="{{ asset('js/script.js') }}" type="text/javascript"></script>

</body>

</html>
