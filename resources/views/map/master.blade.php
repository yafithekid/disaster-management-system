<?php
// if(!isset(session)) {
//     session = $this->session();
// }
echo session('response');

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>DIMAS</title>

    <!-- Bootstrap Core CSS -->
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="/assets/leaflet/leaflet.css" />

    <!-- Mapbox -->
    <link href='https://api.mapbox.com/mapbox.js/v2.4.0/mapbox.css' rel='stylesheet' />

    <!-- Daterange picker -->
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/latest/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/style.css" />
    <style>
        body {
            padding-top: 70px;
            /* Required padding for .navbar-fixed-top. Remove if using .navbar-static-top. Change if height of navigation changes. */
        }
    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body onload="loadMap()">

<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Disaster Management System</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li>
                    <a href="#">About</a>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>
<!-- Page Content -->
<div class="container">

    <div class="row">
        <div id="mapid">
        </div>
    </div>
    <div class="row">
        @yield('form')
        
    </div>
    <div class="row">
            <h3>Executed Query</h3>
        </div>

        <div class="row" id="executedQuery" name="executedQuery">
        </div>

        <div class="row">
            <h3>Result</h3>
        </div>

        <div class="row">
            @section('counter')
            @show
        </div>

        <div class="row" id="resultSet" name="resultSet">
            <!-- TODO: Use table; with angularJS maybe? -->
        </div>
</div>

<!-- /.container -->

<!-- jQuery Version 1.11.1 -->
<script src="/assets/bootstrap/js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="/assets/bootstrap/js/bootstrap.min.js"></script>

<!-- Leaflet JavaScript -->
<script src="/assets/leaflet/leaflet.js"></script>
<script src="/assets/leaflet/app.js"></script>

<!-- Mapbox -->
<script src='https://api.mapbox.com/mapbox.js/v2.4.0/mapbox.js'></script>

<!-- Daterange picker -->
<script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
@yield('script')
</body>

</html>
