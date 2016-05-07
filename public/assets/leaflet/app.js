// MAP VISUALIZATION

// var L.mapbox.accessToken = 'pk.eyJ1IjoiaGF5eXVoYW5pZmFoIiwiYSI6ImNpbm9vamR4cTEwM2x1MmtqdHZ3eTQzMDQifQ.jPC0Z8DKOMsJmrRaXBAAmA';

var mymap = new L.map('mapid', {
	center: [-1.994714, 120.234375],
	zoom: 4
});
L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {attribution: 'copyright OpenStreetMap contributors'}).addTo(mymap);

var geojsonFeature = {
    "type": "Feature",
    "geometry": {
        "type": "Point",
        "coordinates": [-104.99404, 39.75621]
    }
};
L.geoJson(geojsonFeature).addTo(mymap);

var geojsonObj = {
	"type" : "Feature",
	"geometry" : {
		"type" : "",
		"coordinates" : ""
	}
};
// console.log(geojsons);
// L.geoJson(geojsons).addTo(mymap);


// OPTIONS POPULATION

function populateDistrict(province) {
		// var districtSelect = '';
		var districtSelect2 = $('select[name="district"]');
		if (!districtSelect2.attr('disabled')) {
			districtSelect2.empty();
			var optUnset = document.createElement('option');
			optUnset.text = "Unset";
			optUnset.value = null;
			districtSelect2.append(optUnset);
		}

		//TODO: call controller to query district.
		$.get('http://localhost:8000/index/populate-districts', {
			province: province
		}).done(function(districtOpts) {
			districtSelect2.prop('disabled', false);
			console.log(districtOpts);
			var opts = districtOpts["districtOpts"];
			// districtSelect = '<select name="district" id="district" onchange="populateSubdistrict(this.value)">';
			for (var i = 0; i < opts.length; i++) {
				var optval = document.createElement('option');
				optval.text = opts[i];
				optval.value = opts[i];
				districtSelect2.append(optval);
				// var temp = districtSelect + "<option value=\"" + opts[i]
				// 			+ "\">" + opts[i] 
				// 			+ "</option>";
				// districtSelect = temp;
			}

			// console.log(districtSelect);
			// document.getElementById('district').innerHTML = districtSelect + "</select>";
			// districtOpts.forEach(function(entry) {
			// 	var currDistrict = entry;
			// 	districtSelect.concat("<option value=\"");
			// 	districtSelect.concat(currDistrict);
			// 	districtSelect.concat("\">");
			// 	districtSelect.concat(currDistrict);
			// 	districtSelect.concat("</option>");
			// });
		}).fail(function(data) {
			alert("error");
		});
		
}

// document.getElementById('province').addEventListener("change", function(province){
// 	console.log(province);
// });
// provinceDD.onchange = function(province) {
// 	console.log(province);
// 	var districtSelect = '';
// 	//TODO: call controller to query district.
// 	$.get('http://localhost:8000/index/populate-districts', {
// 		province: province
// 	}).done(function(data) {
// 		districtSelect = '<select name="district" id="district">';
// 		districtOpts.forEach(function(entry) {
// 			var currDistrict = entry;
// 			districtSelect.concat("<option value=\"");
// 			districtSelect.concat(currDistrict);
// 			districtSelect.concat("\">");
// 			districtSelect.concat(currDistrict);
// 			districtSelect.concat("</option>");
// 		});
// 	}).fail(function(data) {
// 		alert("error");
// 	});
// 	document.getElementById('district').innerHTML = districtSelect;
// };