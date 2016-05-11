@extends('map.master')
@section('form')

<form id="formRefugeCamp">

    <fieldset class="form-group" >
        
        <label for="locationInput">Location</label>
        <div class="input-group">
            <div class="row">
                <div class="col-md-3">
                    <select class="form-control" id="province" name="province" onchange="populateDistrict(this.value)">
                        <option value="">Province unset..</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-control" id="district" name="district" disabled="disabled" onchange="populateSubdistrict(this.value)">
                        <option value="">Unset</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-control" id="subdistrict" name="subdistrict" disabled="disabled" onchange="populateVillage(this.value)">
                        <option value="">Unset..</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-control" id="village" name="village" disabled="disabled">
                        <option value="">Village unset..</option>
                    </select>
                </div>
            </div>
        </div>

    </fieldset>
    <button type="button" class="btn btn-primary" id="executeButton">Execute!</button>
</form>


@endsection
@section('script')
<script>
    populateOpts();
    $('#executeButton').click(function(e) {
        var province = $('#province').val();
        var district = $('#district').val();
        var subdistrict = $('#subdistrict').val();
        var village  =$('#village').val();
        var formData = {};

        if (province != '') formData['province'] = province;
        if (district != '') formData['district'] = district;
        if (subdistrict != '')  formData['subdistrict'] = subdistrict;
        if (village != '')  formData['village'] = village;
        
        console.log(formData);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type : "GET",
            url : "/dimas/refuge-camps",
            data : formData
        }).done(function(data) {
            console.log(data);
            var execQuery = data["executedQuery"];
            var resultSet = data["resultSet"];
            
            var execQueryField = $('div[name="executedQuery"]');
            execQueryField.empty();
            var textContent = document.createElement('p');
            var node = document.createTextNode(execQuery);
            textContent.appendChild(node);
            execQueryField.append(textContent);

            var resultField = $('div[name="resultSet"]');
            resultField.empty();
            var tableElement = document.createElement('table');
            
            var layerGroup = L.layerGroup();
            //var coordinates = [];
            resultSet.forEach(function(datum) {
                console.log(datum.location);
                var geoJson = L.geoJson(datum.location);
                var x = datum.location.coordinates[1];
                var y = datum.location.coordinates[0];
                //coordinates.push([x,y]);
                mymap.panTo(new L.LatLng(x,y));
                mymap.setZoom(13);
                layerGroup.addLayer(geoJson).addTo(mymap);
            });

            for (var i = 0; i < resultSet.length; i++) {
                    var tableRow = tableElement.insertRow(i);
                    var ctr = 0;
                    for (var k in resultSet[i]) {
                        var cell = tableRow.insertCell(ctr);
                        cell.innerHTML = resultSet[i][k];
                        ctr++;
                }
                    
            }

            
          

            resultField.append(tableElement);
        }).fail(function(data) {
            console.log('Error: ', data);


        });
    });
    
</script>
@endsection