<?php

namespace App\Http\Controllers;


class MapController extends Controller
{
    public function getDisasterEvent(){
        return view('map.disaster_event');
    }

    public function getMedicalFacilities(){
        return view('map.medical_facilities');
    }
}