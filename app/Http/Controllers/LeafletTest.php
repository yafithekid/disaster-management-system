<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use JavaScript;
use App\Http\Controllers\Controller;

class LeafletTest extends Controller
{
    //
    public function getFranceRegion() {
    // 	$rawSQL = "SELECT row_to_json(fc)
			 // FROM ( SELECT 'FeatureCollection' As type, array_to_json(array_agg(f)) As features
			 // FROM (SELECT 'Feature' As type
			 //    , ST_AsGeoJSON(lg.geom)::json As geometry
			 //    , row_to_json((SELECT l FROM (SELECT gid, name_1) As l
			 //      )) As properties
			 // FROM regions As lg   ) As f )  As fc";
    	$resultset = DB::select('select *, ST_AsGeoJSON(ST_Transform(R.geom,4326)) as geojson from regions R where gid = ?', [12]);
    	// echo(count($resultset));
    	// $arrOfLine = [];
    	// for ($i = 0; $i < count($resultset); $i++) {
    	// 	$json = $resultset[$i]->geojson;
	    // 	$json = json_decode($json);
	    // 	$point = \GeoJson\GeoJson::jsonUnserialize($json);
	    // 	$arrOfLine[$i] = $point;
    	// }
    	$json = $resultset[0]->geojson;
    	$json = json_decode($json);
    	$point = \GeoJson\GeoJson::jsonUnserialize($json);

    	JavaScript::put([
    		'geoms' => $resultset,
    		'geojsons' => $point
    	]);
    	return view('index', ['geoms' => $resultset]);
    }
}
