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
    	$resProvince = DB::table('villages')->distinct()->orderBy('province', 'asc')->pluck('province');
    	$resDisasterType = DB::table('disasters')->distinct()->orderBy('type', 'asc')->pluck('type');
    	// JavaScript::put([
    	// 	'villageOpts' => $resVillages
    	// ]);
    	return [
    		'provinces' => $resProvince,
    		'types' => $resDisasterType
    		];
    }

    public function populateDistricts(Request $request) {
    	$provinceName = $request->input("province");
    	$resDistrict = DB::table('villages')->distinct()->where('province', $provinceName)->orderBy('district', 'asc')->pluck('district');
    	JavaScript::put([
    			'districtOpts' => $resDistrict
    		]);
    	return ['districtOpts' => $resDistrict];
    }

    public function populateSubdistricts(Request $request) {
    	$districtName = $request->input("district");
    	$resSubdistrict = DB::table('villages')->distinct()->where('district', $districtName)->orderBy('subdistrict', 'asc')->pluck('subdistrict');
    	JavaScript::put([
    			'subdistrictOpts' => $resSubdistrict
    		]);
    	return ['subdistrictOpts' => $resSubdistrict];
    }

    public function populateVillages(Request $request) {
    	$subdistrictName = $request->input("subdistrict");
    	$resVillage = DB::table('villages')->distinct()->where('subdistrict', $subdistrictName)->orderBy('name', 'asc')->pluck('name', 'id');
    	JavaScript::put([
    			'villageOpts' => $resVillage
    		]);
    	return ['villageOpts' => $resVillage];
    }
}
