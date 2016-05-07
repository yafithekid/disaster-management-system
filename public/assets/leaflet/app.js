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
console.log(geojsons);
L.geoJson(geojsons).addTo(mymap);


// OPTIONS POPULATION

var provinceDD = document.getElementById('province');
provinceDD.onchange = function(province) {
	//TODO: call controller to query district.
};