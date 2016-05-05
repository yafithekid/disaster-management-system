// var L.mapbox.accessToken = 'pk.eyJ1IjoiaGF5eXVoYW5pZmFoIiwiYSI6ImNpbm9vamR4cTEwM2x1MmtqdHZ3eTQzMDQifQ.jPC0Z8DKOMsJmrRaXBAAmA';

var mymap = new L.map('mapid', {
	center: [48.85661, 2.35222],
	zoom: 5
});
L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {attribution: 'copyright OpenStreetMap contributors'}).addTo(mymap);
// var arrLength = geoms.length;
// for (var i = 0; i < arrLength; i++) {
	
// }


var geojsonFeature = {
    "type": "Feature",
    "geometry": {
        "type": "Point",
        "coordinates": [-104.99404, 39.75621]
    }
};
L.geoJson(geojsonFeature).addTo(mymap);

// var tilelayer = L.tileLayer('https://api.mapbox.com/v4/mapbox.satellite/{z}/{x}/{y}.png?access_token=' + L.mapbox.accessToken, {
//     attribution: '© <a href="https://www.mapbox.com/map-feedback/">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
// });
var geojsonObj = {
	"type" : "Feature",
	"geometry" : {
		"type" : "",
		"coordinates" : ""
	}
};
console.log(geojsons);
L.geoJson(geojsons).addTo(mymap);
// geojsonObj.geometry.type=geoms[0]["geojson"]["type"];
// geojsonObj.geometry.coordinates=geoms[0]["geojson"]["coordinates"];
// L.geoJson(json_encode(geoms[0]["row_to_json"])).addTo(mymap);


// L.geoJson(geoms[0]["geojson"]).addTo(mymap);
// console.log(geoms[0]["type"]);