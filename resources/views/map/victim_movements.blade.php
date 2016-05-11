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
            var resultTable = createResultTable();
            victimData = data.resultSet;
            sql = data.executedQuery;

            if(layerGroup.getLayers().length>0){
                layerGroup.clearLayers(addTypeDiv(victimData[0]));
            }

            var resultTableBody = resultTable.find("tbody");
            resultTableBody.append(addTypeRow(victimData[0]));
            victimData.forEach(function(datum){
                var marker = new L.geoJson(datum.point);
                x = datum.point.coordinates[1];
                y = datum.point.coordinates[0];
                coordinates.push([x,y]);
                layerGroup.addLayer(marker);
                var datumTr = addDatumRow(datum);
                resultTableBody.append(datumTr);
            }); 
            
            mymap.setZoom(4);
            mymap.panTo(new L.LatLng(x,y));
            mymap.setZoom(13);
            var pathline = new L.polyline(coordinates);
            layerGroup.addLayer(pathline);
            layerGroup.addTo(mymap);
            $("#executedQuery").html(sql);
            $("#resultSet").append(resultTable);
        });
    }

    function createResultTable(){
        var table = $(document.createElement('table'));
        table.attr("class","table table-striped table-condensed");
        table.append($(document.createElement('tbody')))
        return table;
    }

    function addTypeRow( data ){
        var tr = $(document.createElement('tr'));
        // div.attr("class","row");
        // div.css("background-color",'#CCC');
        // tr.css("border","1px solid black");
        for (var key in data){
            var tempTh = $(document.createElement('th'));
            // tempTh.attr("id",key);
            // tempTh.attr("class","col-md-1");
            tempTh.append(key);
            tr.append(tempTh);
        }
        return tr;
    }
    function addDatumRow( data ){
        var tableRow = $(document.createElement('tr'));
        // div.attr("class","row");
        // div.css("background-color",'#DDD');
        // div.css("border","1px solid #AAA");
        for (var key in data){
            var tempTd = $(document.createElement('td'));
            // tempDiv.attr("id",key);
            // tempDiv.attr("class","col-md-1");
            if(key=='point'){
                tempTd.append(data[key].coordinates);
            }
            else{
                tempTd.append(data[key]);
            }
            tableRow.append(tempTd);    
        }
        return tableRow;
    }
    $("#resultSet").css("padding-bottom", "10px");
</script>
@endsection