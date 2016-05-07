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
    
    	$resultset = DB::select('select *, ST_AsGeoJSON(ST_Transform(R.geom,4326)) as geojson from villages R where id = ?', [1]);
    	
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
