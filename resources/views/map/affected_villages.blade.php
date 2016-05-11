@extends('map.master')
@section('form')
    <a onclick="refresh()" class="btn btn-primary" id="refreshButton">Run</a>
    <label for="timeInput">Certain Time</label>
    <div class="input-group">
        <div class="row">
            <div class="col-md-3">
                <select class="form-control" id="year" name="year">
                    <option value="">Year unset..</option>
                    <option value="2016">2016</option>
                    <option value="2015">2015</option>
                    <option value="2014">2014</option>
                    <option value="2013">2013</option>
                    <option value="2012">2012</option>
                    <option value="2011">2011</option>
                    <option value="2010">2010</option>
                    <option value="2009">2009</option>
                    <option value="2008">2008</option>
                    <option value="2007">2007</option>
                    <option value="2006">2006</option>
                    <option value="2005">2005</option>
                    <option value="2004">2004</option>
                    <option value="2003">2003</option>
                    <option value="2002">2002</option>
                    <option value="2001">2001</option>
                    <option value="2000">2000</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-control" id="month" name="month" onchange="generateDayOpts(this.value, year.value)">
                    <option value="">Month unset..</option>
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-control" id="day" name="day" disabled="disabled">
                    <option value="">Day unset..</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-control" id="type">
                    <option value="">Type unset</option>
                    <option value="flood">Banjir</option>
                    <option value="earthquake">Gempa</option>
                    <option value="wildfire">Kebakaran Hutan</option>
                    <option value="mud flow">Longsor</option>
                </select>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script>
    var addedJsons = [];
    var renderGeoData = function(geoData){
        addedJsons.forEach(function(layer){
            mymap.removeLayer(layer);
        });
        geoData.forEach(function(datum,id){
            var geoJson = L.geoJson(datum.geom);
            if (id == 0){
                var firstCoord = datum.geom.coordinates[0][0][0];
                mymap.panTo(new L.LatLng(firstCoord[1], firstCoord[0]));
                mymap.setZoom(13);
            }
            addedJsons.push(geoJson.addTo(mymap));
        });
    };
    var refresh = function(){
        var year = $("#year").val();
        var month = $("#month").val();
        var day = $("#day").val();
        var type = $("#type").val();
        var disasEventId = '{{$disaster_event_id}}';

        var formData = {
            disasterEventId: disasEventId
        };
        if (year != ''){
            formData["year"] = year;
        }
        if (month != ''){
            formData["month"] = month;
        }
        if (day != ''){
            formData["day"] = day;
        }
        if (type != ''){
            formData['type'] = type;
        }
        console.log(formData);
//        $.ajax({
//            type : "GET",
//            url : "/dimas/disaster-events",
//            data : formData
//        }).done(function(data){
//            console.log(data);
//        }).fail(function(data){
//            alert(data);
//        });
        $.ajax({
            type: "GET",
            url: "/dimas/villages-affected",
            data:formData
        }).done(function(data){
            $("#refreshButton").hide();
            $("#executedQuery").html(data.executedQuery);
            renderGeoData(data.resultSet);
            var table = document.createElement("table");
            $(table).attr("class","table table-striped table-condensed");
            $(table).append("<tr><th>Village Name</th><th>Weather</th><th>Start</th></tr>");
            data.resultSet.forEach(function(dArea){
                $(table).append("<tr><td>"+dArea.name+"</td><td>"+dArea.weather_condition+"</td><td>"+dArea.start+"</td></tr>");
            });
            $("#resultSet").append(table);
//            $("#resultSet").append(appended);
        }).fail(function(data){
            alert(data);
        });
        $.ajax({
            type: "GET",
            url: "/dimas/aggregated-areas/"+disasEventId,
            data: formData
        }).done(function(data){
            var resultSet = data.resultSet;
            resultSet.forEach(function(dArea){
                var geoJson = L.geoJson(dArea.area);
                var layer = geoJson.addTo(mymap);
                layer.setStyle({fillColor:"#ff0000"});
            });
        }).fail(function(data){

        });
    }

</script>
@endsection