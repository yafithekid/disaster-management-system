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


Route::get('/', ['uses' => 'LeafletTest@getFranceRegion', function () {
    return view('index');
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
