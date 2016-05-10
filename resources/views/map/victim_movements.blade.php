@extends('map.master')
@section('form')
    <script>
        var refresh2 = function(){
            var victimId = $('#village_id').val();
            $.get( "/dimas/victim-movements/"+victimId, function( data ) {
                var coordinates = [];
                data.forEach(function(datum,index){
                    var x = datum.point.coordinates[1];
                    var y = datum.point.coordinates[0];
                    coordinates.push([x,y]);  
                    var geoJson = L.geoJson(datum.point).addTo(mymap);

                    console.log(datum);   
                });
                var pathline = L.polyline(coordinates).addTo(mymap);
            });
        }
    </script>
    <label>Victim ID</label>
    <input id="village_id"/>
    <a onclick="refresh2()" class="btn btn-primary">Filter</a>
@endsection
@section('script')
<script>
    // var refresh = function(){
    //     var villageId = 10346;
    //     $.get( "/dimas/medical-facilities?villageId="+villageId, function( data ) {
    //         console.log(data);
    //     });
    // }
    
</script>
@endsection