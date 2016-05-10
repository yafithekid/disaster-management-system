@extends('map.master')
@section('form')
    <label>Victim ID</label>
    <input id="village_id"/>
    <a onclick="refresh()" class="btn btn-primary">Show</a>
@endsection
@section('script')
<script>
    var victimData;
    var layerGroup = L.layerGroup();
    var refresh = function(){
       
        var victimId = $('#village_id').val();  
        // var TIMEOUT=1000;
        var coordinates = [];

        $.get( "/dimas/victim-movements/"+victimId, function( data ) {
            layerGroup.clearLayers();
            victimData = data;
            victimData.forEach(function(datum){
                console.log(datum.point);
                var geoJson = L.geoJson(datum.point);
                var x = datum.point.coordinates[1];
                var y = datum.point.coordinates[0];
                coordinates.push([x,y]);
                // setTimeout(function(){
                mymap.panTo(new L.LatLng(x,y));
                mymap.setZoom(13);
                layerGroup.addLayer(geoJson).addTo(mymap);
                // },TIMEOUT);   
            }); 
            
            var pathline = L.polyline(coordinates)
            layerGroup.addLayer(pathline).addTo(mymap);
        
        });
    }
</script>
@endsection