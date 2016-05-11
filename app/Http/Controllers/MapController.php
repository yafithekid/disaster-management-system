<?php

namespace App\Http\Controllers;


use App\Services\DisasterEventQueryBuilder;
use Illuminate\Http\Request;

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


    public function getRefugeCamps(){
        return view('map.refuge_camps');
    }

    public function getVictims($id)
    {
        return view('map.victims', ['id' => $id]);
    }
    
    public function getVictimMovements(Request $request){
    	$array = [];
        if ($request->has('id')){
            $array ['id'] = $request->input('id');
        }
        return view('map.victim_movements',$array);
    }
    
    public function getAffectedVillages($disaster_event_id)
    {
        return view('map.affected_villages',compact('disaster_event_id'));
    }
}