<?php
    // print_r($villages);
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
            <div class="col-lg-12 text-center">
                <h1>Indonesia Disaster Management System</h1>
                <div class="lead">Created to fulfill IF4040 course assignment</div>
            </div>
        </div>
        <!-- /.row -->

        <div class="row">
            <div id="mapid">
            </div>
        </div>

        <div class="row">
            <h3>Query Categories</h3>
        </div>

        <div class="row">
            <div class="btn-group btn-group-justified" role="group" aria-label="Query Categories">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalDisasterEvents">Disaster Events</button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalVillages">Villages</button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalVictims">Victims</button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" href="/populateOpts" class="btn btn-primary" data-toggle="modal" data-target="#modalRefugeeCamps">Refugee Camps</button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalMedicalFacilities">Medical Facilities</button>
                </div>  
            </div>
        
        </div> <!-- row of Buttons -->

        <!-- Group of Modals -->
        <div class="modal fade" id="modalDisasterEvents" tabindex="-1" role="dialog" aria-labelledby="modalDisasterEventsLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modalDisasterEventsLabel">Disaster Events Query</h4>
                    </div>
                    <div class="modal-body">
                        <form>
                            <fieldset class="form-group">
                                <label for="timeInput">Certain Time</label>
                                <div class="input-group">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <select class="form-control" id="month" name="month">
                                                <option value="">Month unset..</option>
                                                <option value="1">January</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-control" id="day" name="day">
                                                <option value="">Day unset..</option>
                                                <option value="1">1</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-control" id="year" name="year">
                                                <option value="">Year unset..</option>
                                                <option value="2016">2016</option>
                                            </select>
                                        </div>
                                    </div>
                                </div> 
                                
                                <label for="periodInput">Periods</label>
                                <div class="input-group">
                                    <input type="text" name="disasterperiods" value="" placeholder="Disaster periods..." >
                                </div>

                                <label for="locationInput">Location</label>
                                <div class="input-group">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <select class="form-control" id="province" name="province">
                                                <option value="">Province unset..</option>
                                                <?php
                                                    foreach($provinces as $opt) {
                                                        echo "<option value".$opt.">".$opt."</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-control" id="district" name="district">
                                                <option value="">District unset..</option>
                                                <?php
                                                    foreach($districts as $opt) {
                                                        echo "<option value".$opt.">".$opt."</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-control" id="subdistrict" name="subdistrict">
                                                <option value="">Subdistrict unset..</option>
                                                <?php
                                                    foreach($subdistricts as $opt) {
                                                        echo "<option value".$opt.">".$opt."</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-control" id="village" name="village">
                                                <option value="">Village unset..</option>
                                                <?php
                                                    foreach($villages as $id => $name) {
                                                        echo "<option value".$name.">".$id."</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <label for="disasterTypeInput">Disaster Type</label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <select class="form-control" id="disasterType" name="disasterType">
                                            <option value="">Type unset..</option>
                                            <?php
                                                foreach($types as $opt) {
                                                    echo "<option value".$opt.">".$opt."</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                            </fieldset>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Execute!</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalVictims" tabindex="-1" role="dialog" aria-labelledby="modalVictimMovementsLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modalVictimMovementsLabel">Victims Query</h4>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Execute!</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalVillages" tabindex="-1" role="dialog" aria-labelledby="modalVillagesLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modalVillagesLabel">Villages Query</h4>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Execute!</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalRefugeeCamps" tabindex="-1" role="dialog" aria-labelledby="modalRefugeeCampsLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modalRefugeeCampsLabel">Refugee Camps Query</h4>
                    </div>
                    
                    <form method="get" action='/dimas/refuge-camps'>
                    <div class="modal-body">
                    <?php 
                        // echo Form::open(array('action' => 'DimasController@getRefugeCamps', 'method' => 'get'));
                        // echo Form::select('province', $provinces, '');
                        // echo Form::select('district', $districts, '');
                        // echo Form::select('subdistrict', $subdistricts, '');
                        // echo Form::select('village', array(), '');
                        // echo Form::submit('Execute!');
                        // echo Form::close();
                    ?>
                        <!-- <form method="get" action='DimasController@getRefugeCamps'> -->
                            <fieldset class="form-group">
                                <label for="locationInput">Location</label>
                                <div class="input-group">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <select class="form-control" id="province" name="province" onchange="populateDistrict(this.value)">
                                                <option value="">Province unset..</option>
                                                <?php
                                                    foreach($provinces as $opt) {
                                                        echo "<option value".$opt.">".$opt."</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-control" id="district" name="district" disabled="disabled">
                                                <option value="">District unset..</option>
                                                <?php
                                                    // foreach($districts as $opt) {
                                                    //     echo "<option value".$opt.">".$opt."</option>";
                                                    // }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-control" id="subdistrict" name="subdistrict" disabled="disabled">
                                                <option value="">Subdistrict unset..</option>
                                                <?php
                                                    // foreach($subdistricts as $opt) {
                                                    //     echo "<option value".$opt.">".$opt."</option>";
                                                    // }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-control" id="village" name="village" disabled="disabled">
                                                <option value="">Village unset..</option>
                                                <?php
                                                    // foreach($villages as $id => $name) {
                                                    //     echo "<option value=".$id.">".$name."</option>";
                                                    // }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        <!-- </form> -->
                        <?php
                            // echo Form::submit('Execute!');
                            // Form::close();
                        ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Execute!</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalMedicalFacilities" tabindex="-1" role="dialog" aria-labelledby="modalMedicalFacilitiesLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modalMedicalFacilitiesLabel">Medical Facilities Query</h4>
                    </div>
                    <div class="modal-body">
                        <form>
                            <fieldset class="form-group">
                                <label for="locationInput">Location</label>
                                <div class="input-group">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <select class="form-control" id="province" name="province">
                                                <option value="">Province unset..</option>
                                                <?php
                                                    foreach($provinces as $opt) {
                                                        echo "<option value".$opt.">".$opt."</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-control" id="district" name="district">
                                                <option value="">District unset..</option>
                                                <?php
                                                    foreach($districts as $opt) {
                                                        echo "<option value".$opt.">".$opt."</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-control" id="subdistrict" name="subdistrict">
                                                <option value="">Subdistrict unset..</option>
                                                <?php
                                                    foreach($subdistricts as $opt) {
                                                        echo "<option value".$opt.">".$opt."</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-control" id="village" name="village">
                                                <option value="">Village unset..</option>
                                                <?php
                                                    foreach($villages as $id => $name) {
                                                        echo "<option value".$name.">".$id."</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </fieldset>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Execute!</button>
                    </div>
                </div>
            </div>
        </div>

        
        <!-- end of Modals -->

        <div class="row">
            <h3>Executed Query</h3>
        </div>

        <div class="row">
            <p>Lorem ipsum</p>
        </div>

        <div class="row">
            <h3>Result</h3>
        </div>

        <div class="row">
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
    <script type="text/javascript">
        $(function() {
            $('input[name="disasterperiods"]').daterangepicker();
        });
    </script>

</body>

</html>
