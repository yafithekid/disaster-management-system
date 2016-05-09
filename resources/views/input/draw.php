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

    <title>DIMAS</title>

    <!-- Bootstrap Core CSS -->
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="/assets/leaflet/leaflet.css" />

    <link rel="stylesheet" href="/assets/leaflet-draw/leaflet.draw.css"/>

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

<body>

    <div class="row">
        <div id="map" style="height:500px;">
        </div>
    </div>
    <div class="row" style="padding:20px;
    ">
        <div id="json">konten</div>
    </div>
<!-- /.container -->

<!-- jQuery Version 1.11.1 -->
<script src="/assets/bootstrap/js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="/assets/bootstrap/js/bootstrap.min.js"></script>

<!-- Mapbox -->
<script src='https://api.mapbox.com/mapbox.js/v2.4.0/mapbox.js'></script>
<!-- Leaflet JavaScript -->
<script src="/assets/leaflet/leaflet.js"></script>
<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-draw/v0.2.3/leaflet.draw.js'></script>



<!-- Daterange picker -->
<script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
<script type="text/javascript">
    // create a map in the "map" div, set the view to a given place and zoom
    var map = new L.map('map').setView([-6.828685, 107.608743], 13);

    // add an OpenStreetMap tile layer
    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Initialize the FeatureGroup to store editable layers
    var drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    // Initialize the draw control and pass it the FeatureGroup of editable layers
    var drawControl = new L.Control.Draw({
        edit: {
            featureGroup: drawnItems
        }
    });
    map.addControl(drawControl);

    map.on('draw:created', function(e) {
        var type;
        if (e.layerType=='polyline'){
            type = "LineString";
        } else if (e.layerType == 'marker'){
            type = "Point";
        } else {
            type = "Polygon";
        }
        // alert(type);
        drawnItems.addLayer(e.layer);
        var a = "ST_GeomFromGeoJSON({\"type\":\""+type+"\",\"coordinates\":[";

        var latlngs = e.layer.getLatLngs();
        latlngs.forEach(function(latlng,i){
            if (i > 0) a = a + ",";
            a = a + "["+latlng.lat+","+latlng.lng+"]";
        });
        a += "]})";
        $("#json").html(a);
    });
</script>

</body>

</html>
