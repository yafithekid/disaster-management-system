<?php

namespace App\Http\Controllers;

use App\Services\DisasterEventQueryBuilder;
use App\Services\MedicalFacilityQueryBuilder;
use App\Services\RefugeCampQueryBuilder;
use App\Services\VictimQueryBuilder;
use App\Services\VillageQueryBuilder;
use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use JavaScript;

class IndexController extends Controller
{
    //
    public function populateOpts() {
    	$resVillages = DB::table('villages')->orderBy('name', 'asc')->pluck('id', 'name');
    	$resSubdistrict = DB::table('villages')->distinct()->orderBy('subdistrict', 'asc')->pluck('subdistrict');
    	$resDistrict = DB::table('villages')->distinct()->orderBy('district', 'asc')->pluck('district');
    	$resProvince = DB::table('villages')->distinct()->orderBy('province', 'asc')->pluck('province');
    	$resDisasterType = DB::table('disasters')->distinct()->orderBy('type', 'asc')->pluck('type');
    	// JavaScript::put([
    	// 	'villageOpts' => $resVillages
    	// ]);
    	return view('index', [
    		'villages' => $resVillages,
    		'subdistricts' => $resSubdistrict,
    		'districts' => $resDistrict,
    		'provinces' => $resProvince,
    		'types' => $resDisasterType
    		]);
    }
}
