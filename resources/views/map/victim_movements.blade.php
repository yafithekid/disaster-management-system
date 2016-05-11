@extends('map.master')
@section('form')
    <label>Victim ID</label>
    <input id="village_id"/>
    <a onclick="refresh()" class="btn btn-primary">Show</a>
@endsection
@section('script')
<script>
    
    var victimData; 
    var sql;
    var layerGroup = new L.layerGroup();
    var refresh = function(){
        $("#resultSet").empty();
        var victimId = $('#village_id').val();
        $.get( "/dimas/victim-movements/"+victimId, function( data ) {
            var coordinates = [];
            var x,y;            
            victimData = data.resultSet;
            sql = data.executedQuery;
            
            if(layerGroup.getLayers().length>0){
                layerGroup.clearLayers();
            }

            $("#resultSet").append(addTypeDiv(victimData[0]));
            victimData.forEach(function(datum){
                var marker = new L.geoJson(datum.point);
                x = datum.point.coordinates[1];
                y = datum.point.coordinates[0];
                coordinates.push([x,y]);
                layerGroup.addLayer(marker);
                var datumDiv = addDatumDiv(datum);
                $("#resultSet").append(datumDiv);
            }); 
            
            mymap.setZoom(4);
            mymap.panTo(new L.LatLng(x,y));
            mymap.setZoom(13);
            var pathline = new L.polyline(coordinates);
            layerGroup.addLayer(pathline);
            layerGroup.addTo(mymap);
            $("#executedQuery").html(sql);
        });
    }

    function addTypeDiv( data ){
        var div = $(document.createElement('div'));
        div.attr("class","row");
        div.css("background-color",'#CCC');
        div.css("border","1px solid #AAA");
        for (var key in data){
            var tempDiv = $(document.createElement('div'));
            tempDiv.attr("id",key);
            tempDiv.attr("class","col-md-1");
            tempDiv.append(key);
            div.append(tempDiv);
        }
        return div;
    }
    function addDatumDiv( data ){
        var div = $(document.createElement('div'));
        div.attr("class","row");
        div.css("background-color",'#DDD');
        div.css("border","1px solid #AAA");
        for (var key in data){
            var tempDiv = $(document.createElement('div'));
            tempDiv.attr("id",key);
            tempDiv.attr("class","col-md-1");
            if(key=='point'){
                tempDiv.append(data[key].coordinates);
            }
            else{
                tempDiv.append(data[key]);
            }
            div.append(tempDiv);    
        }
        return div;
    }
    $("#resultSet").css("padding-bottom", "10px");
</script>
@endsection