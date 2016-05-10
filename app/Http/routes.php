<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/draw',['uses'=>'InputController@getDraw']);

Route::get('/map/disaster_event',['uses'=>'MapController@getDisasterEvent']);
Route::get('/map/med_fac',['uses'=>'MapController@getMedicalFacilities']);

Route::get('/index', ['as' => 'index', 'uses' => 'IndexController@populateOpts', function () {
    return view('index');
}]);
Route::get('/index/populate-districts', ['uses' => 'IndexController@populateDistricts']);
Route::get('/index/populate-subdistricts', ['uses' => 'IndexController@populateSubdistricts']);
Route::get('/index/populate-villages', ['uses' => 'IndexController@populateVillages']);
Route::get('/index/populate-provinces', ['uses' => 'IndexController@populateOpts']);
Route::get('/result', function() {
	return view('result');
});

Route::get('/', ['as' => 'welcome', function () {
	return view('welcome');
}]);

Route::get('/test',['uses'=>'Auth\AuthController@test']);

Route::get('/dimas/disaster-events',['uses'=>'DimasController@getDisasterEvents']);
Route::get('/dimas/disaster-event-changes',['uses'=>'DimasController@getDisasterEventChanges']);
Route::get('/dimas/victim-movements/{id}',['uses'=>'DimasController@getVictimMovements']);
Route::get('/dimas/villages-affected',['uses'=>'DimasController@getVillagesAffected']);
Route::get('/dimas/victims',['uses'=>'DimasController@getVictims']);
Route::get('/dimas/refuge-camps',['uses'=>'DimasController@getRefugeCamps']);
Route::get('/dimas/medical-facilities',['uses'=>'DimasController@getMedicalFacilities']);
Route::get('/dimas/number-of-victims',['uses'=>'DimasController@getNumberOfVictims']);
Route::get('/dimas/test-method', ['uses' => 'DimasController@testMethod']);

Route::get('/datafix/reverse-med-fac',['uses'=>'DatafixController@reverseMedFac']);
Route::get('/datafix/reverse-ref-cmp',['uses'=>'DatafixController@reverseRefCmp']);
Route::get('/datafix/reverse-vic-loc',['uses'=>'DatafixController@reverseVicLoc']);
Route::get('/datafix/reverse-disaster-area',['uses'=>'DatafixController@reverseDisasterArea']);
Route::get('/datafix/push-disaster-area',['uses'=>'DatafixController@pushDisasterArea']);
