// MAP VISUALIZATION

var mymap = new L.map('mapid', {
		center: [-1.994714, 120.234375],
		zoom: 4,
		worldCopyJump: true,
	});
L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {attribution: 'copyright OpenStreetMap contributors'}).addTo(mymap);
var geoJsonLayer = L.geoJson().addTo(mymap);
// var L.mapbox.accessToken = 'pk.eyJ1IjoiaGF5eXVoYW5pZmFoIiwiYSI6ImNpbm9vamR4cTEwM2x1MmtqdHZ3eTQzMDQifQ.jPC0Z8DKOMsJmrRaXBAAmA';

function loadMap() {
	// var mymap = new L.map('mapid', {
	// 		center: [-1.994714, 120.234375],
	// 		zoom: 4,
	// 		worldCopyJump: true,
	// 	});
	// L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {attribution: 'copyright OpenStreetMap contributors'}).addTo(mymap);
	// var geoJsonLayer = L.geoJson().addTo(mymap);
	var geojsonFeature = {
		    "type": "Feature",
		    "geometry": {
		        "type": "Point",
		        "coordinates": [107.608743, -6.828865]
		    }
	};
	// geoJsonLayer.addData(geojsonFeature);

}

function addMarkerToMap(response) {
	console.log(response);
	var feature = response['features'];
	console.log(feature);
	var geojsonFeature2 = {
	    "type": "Feature",
	    "geometry": {
	        "type": "Point",
	        "coordinates": [107.608743, -6.828865]
	    }
	};
	console.log(geojsonFeature2);
	// geoJsonLayer.addData(geojsonFeature2);

	for (var i = 0; i < feature.length; i++){
		console.log(feature[i]["geometry"]);
		var geojson = feature[i]["geometry"]["coordinates"];
		var swappedLoc = [geojson[1], geojson[0]];
		console.log(swappedLoc);
		feature[i]["geometry"]["coordinates"] = swappedLoc;
		console.log(feature[i]["geometry"]["coordinates"]);
		// var geojson = {
		// 	"type" : "Feature",
		// 	"geometry" : {
		// 		"type" : "Point",
		// 		"coordinates" : [-6.828865, 107.608743]
		// 	}
		// };
		console.log(feature[i]);
		geoJsonLayer.addData(feature[i]);
		// L.geoJson(feature[i]).addTo(mymap);
	}
	// console.log(feature[0]);
	// L.geoJson(feature[0]).addTo(mymap);
}


// OPTIONS POPULATION

function populateDistrict(province) {
	var districtSelect2 = $('select[name="district"]');
	if (!districtSelect2.attr('disabled')) {
		districtSelect2.empty();
		var optUnset = document.createElement('option');
		optUnset.text = "Unset";
		optUnset.value = null;
		districtSelect2.append(optUnset);
	}

	$.get('http://localhost:8000/index/populate-districts', {
		province: province
	}).done(function(districtOpts) {
		districtSelect2.prop('disabled', false);
		console.log(districtOpts);
		var opts = districtOpts["districtOpts"];
		for (var i = 0; i < opts.length; i++) {
			var optval = document.createElement('option');
			optval.text = opts[i];
			optval.value = opts[i];
			districtSelect2.append(optval);
		}
	}).fail(function(data) {
		alert("error");
	});
}

function populateSubdistrict(district) {
	var subdistrictSelect = $('select[name="subdistrict"]');
	if (!subdistrictSelect.attr('disabled')) {
		subdistrictSelect.empty();
		var optUnset = document.createElement('option');
		optUnset.text = "Unset";
		optUnset.value = null;
		subdistrictSelect.append(optUnset);
	}

	$.get('http://localhost:8000/index/populate-subdistricts', {
		district: district
	}).done(function(subdistrictOpts) {
		subdistrictSelect.prop('disabled', false);
		console.log(subdistrictOpts);
		var opts = subdistrictOpts["subdistrictOpts"];
		for (var i = 0; i < opts.length; i++) {
			var optval = document.createElement('option');
			optval.text = opts[i];
			optval.value = opts[i];
			subdistrictSelect.append(optval);
		}
	}).fail(function(data) {
		alert("error");
	});
}

function populateVillage(subdistrict) {
	var villageSelect = $('select[name="village"]');
	if (!villageSelect.attr('disabled')) {
		villageSelect.empty();
		var optUnset = document.createElement('option');
		optUnset.text = "Unset";
		optUnset.value = null;
		villageSelect.append(optUnset);
	}

	$.get('http://localhost:8000/index/populate-villages', {
		subdistrict: subdistrict
	}).done(function(villageOpts) {
		villageSelect.prop('disabled', false);
		console.log(villageOpts);
		var opts = villageOpts["villageOpts"];
		console.log(opts);
		for (var key in opts) {
			if (opts.hasOwnProperty(key)) {
				var optval = document.createElement('option');
				optval.text = opts[key];
				optval.value = key;
				villageSelect.append(optval);	
			}
		}
	}).fail(function(data) {
		alert("error");
	});
}