<?php

namespace App\Http\Controllers;


use App\Services\DisasterEventQueryBuilder;

class MapController extends Controller
{
    public function getDisasterEvent(){
        return view('map.disaster_event');
    }

    public function getDisasterChanges($id)
    {
        return view('map.disaster_changes',['id'=>$id]);
    }

    public function getMedicalFacilities(){
        return view('map.medical_facilities');
    }

    public function getVictims($id)
    {
        return view('map.victims', ['id' => $id]);
    }

    public function getVictimMovements(){
    	return view('map.victim_movements');
    }
    
    public function getAffectedVillages($disaster_event_id)
    {
        return view('map.affected_villages',compact('disaster_event_id'));
    }
}