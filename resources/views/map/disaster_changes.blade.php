@extends('map.master')
@section('form')
    <a onclick="refresh()" class="btn btn-primary" id="simulate">Simulate</a>
@endsection
@section('script')
<script>
    //get disaster event data from server
    var disasEventId = '{{$id}}';
    var data;
    var sql;
    $("#id").hide();
    $.get( "/dimas/disaster-event-changes?id="+disasEventId,function(geoData){
        $("#id").show();
        data = geoData.resultSet;
        sql = geoData.executedQuery;
        $("#executedQuery").html(sql);
    });
    var refresh = function(){
        var TIMEOUT = 1000;
        data.forEach(function(datum,id){
            var geoJson = L.geoJson(datum.region);
            setTimeout(function () {
                var firstCoord = datum.centroid.coordinates;
                mymap.panTo(new L.LatLng(firstCoord[1], firstCoord[0]));
                mymap.setZoom(13);
                var overlay = geoJson.addTo(mymap);
                setTimeout(function(){
                    mymap.removeLayer(overlay);
                },(TIMEOUT));
            },(id*TIMEOUT));

        })
    }

</script>
@endsection