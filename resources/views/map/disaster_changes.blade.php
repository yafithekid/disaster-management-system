@extends('map.master')
<?php $i = 0; ?>
@section('form')
    <div class="col-md-4">
        <label>Type</label>
        <select id="disaster_id" class="form-control">
            <option value="">Unset</option>
            @foreach($disasters as $disaster)
            <option value="{{$disaster->id}}">{{++$i}}. {{$disaster->type}}</option>
            @endforeach
        </select>
        <a onclick="refresh()" class="btn btn-primary" id="simulate">Simulate</a>
    </div>
    <div class="col-md-4">
        <label>Details</label>
        <div id="type"></div>
        <span id="start"></span>
        <span id="end"></span>
    </div>
@endsection
@section('script')
<script>
    //get disaster event data from server
    var disasEventId = '{{$id}}';
    var data;
    var sql;
    $("#id").hide();

    var refresh = function(){
        var TIMEOUT = 1000;
        var disaster_id = $("#disaster_id").val();
        var formData = {};
        formData["id"] = disasEventId;
        if (disaster_id != ''){
            formData["disaster_id"] = disaster_id;
        }
        $.ajax({
            type : "GET",
            url : "/dimas/disaster-event-changes",
            data : formData
        }).done(function(geoData){
            $("#id").show();
            data = geoData.resultSet;
            sql = geoData.executedQuery;
            $("#executedQuery").html(sql);

            data.forEach(function(datum,id){
                var geoJson = L.geoJson(datum.region);
                setTimeout(function () {
                    $("#type").html(datum.type);
                    $("#start").html(datum.start);
                    $("#end").html(datum.end);
                    var firstCoord = datum.centroid.coordinates;
                    mymap.panTo(new L.LatLng(firstCoord[1], firstCoord[0]));
                    mymap.setZoom(13);
                    var overlay = geoJson.addTo(mymap);
                    setTimeout(function(){
                        mymap.removeLayer(overlay);
                    },(TIMEOUT));
                },(id*TIMEOUT));

            });
        });

    }

</script>
@endsection