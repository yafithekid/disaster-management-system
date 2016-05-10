@extends('map.master')
@section('form')
<form id="formDisasterEvent">

    <fieldset class="form-group" >
        <label for="timeInput">Certain Time</label>
        <div class="input-group">
            <div class="row">
                <div class="col-md-4">
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
                <div class="col-md-5">
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
            </div>
        </div> 
        
        <label for="periodInput">Periods</label>
        <div class="input-group">
            <button type="button" class="btn btn-secondary" id="useDateRange">Use Periods</button>
            <input type="text" id="disasterperiods" name="disasterperiods" value="" placeholder="Disaster periods..." >
            <button type="button" class="btn btn-secondary" id="clearDateRange">Clear Periods</button>
        </div>

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

        <label for="disasterTypeInput">Disaster Type</label>
        <div class="row">
            <div class="col-md-3">
                <select class="form-control" id="disasterType" name="disasterType">
                    <option value="">Type unset..</option>
                </select>
            </div>
        </div>

    </fieldset>
    <button type="button" class="btn btn-primary" id="executeButton">Execute!</button>
</form>
@endsection
@section('script')
	<script>
		populateOpts();
	    $('#useDateRange').click(function() {
            $('input[name="disasterperiods"]').daterangepicker();
        });
        $('#clearDateRange').click(function() {
            $('input[name="disasterperiods"]').val('');
        });
    	$('#executeButton').click(function(e) {
    		var formData = {
    			year : $('#year').val(),
    			month : $('#month').val(),
    			day : $('#day').val(),
    			disasterperiods : $('#disasterperiods').val(),
    			province : $('#province').val(),
    			district : $('#district').val(),
    			subdistrict : $('#subdistrict').val(),
    			village : $('#village').val(),
    			disasterType : $('#disasterType').val()
    		};
    		console.log(formData);
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });
    		
            $.ajax({
                type : "GET",
                url : "/dimas/disaster-events",
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
                tableElement.setAttribute('id', 'resultTable');
                // resultField.append(tableElement);
                for (var i = 0; i < resultSet.length; i++) {
                    var tableRow = tableElement.insertRow(i);
                    var ctr = 0;
                    for (var k in resultSet[i]) {
                        var cell = tableRow.insertCell(ctr);
                        cell.innerHTML = resultSet[i][k];
                        ctr++;
                    }
                    var disasterChanges = tableRow.insertCell(ctr);
                    var linkToChanges = document.createElement('a');
                    linkToChanges.innerHTML = "See Changes";
                    linkToChanges.setAttribute('href', 'http://localhost:8000/map/disaster_changes/' + resultSet[i]["id"]);
                    linkToChanges.setAttribute('target', '_blank');
                    disasterChanges.appendChild(linkToChanges);
                    var getAffectedVillages = tableRow.insertCell(ctr+1);
                    var linkToAffected = document.createElement('a');
                    linkToAffected.innerHTML = "Affected Villages";
                    linkToAffected.setAttribute('href', 'http://localhost:8000/map/affected_villages/' + resultSet[i]["id"]);
                    linkToAffected.setAttribute('target', '_blank');
                    getAffectedVillages.appendChild(linkToAffected);
                    var getListVictims = tableRow.insertCell(ctr+2);
                    var linkToVictims = document.createElement('a');
                    linkToVictims.innerHTML = "List Victims";
                    linkToVictims.setAttribute('href', 'http://localhost:8000/map/victims/' + resultSet[i]["id"]);
                    linkToVictims.setAttribute('target', '_blank');
                    getListVictims.appendChild(linkToVictims);
                }
                resultField.append(tableElement);

            }).fail(function(data) {
                console.log('Error: ', data);
            });
    	});
    </script>
@endsection