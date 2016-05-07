<?php

namespace App\Http\Controllers;

use App\Services\DisasterEventQueryBuilder;
use App\Services\MedicalFacilityQueryBuilder;
use App\Services\RefugeCampQueryBuilder;
use App\Services\VictimQueryBuilder;
use App\Services\VillageQueryBuilder;
use GeoJson\GeoJson;
use Illuminate\Database\Connection;
use Illuminate\Http\Request;

use App\Http\Requests;

class DimasController extends Controller
{
    private $db;
    const DEBUG = true;

    public function __construct(Connection $database)
    {
        $this->db = $database;
        if (self::DEBUG)
        $database->listen(function($the_query)
        {
            $query = $the_query->sql;
            $bindings = $the_query->bindings;
            // Format binding data for sql insertion
            foreach ($bindings as $i => $binding) {
                if ($binding instanceof \DateTime) {
                    $bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
                } else if (is_string($binding)) {
                    $bindings[$i] = "'$binding'";
                }
            }

            // Insert bindings into query
            $query = str_replace(array('%', '?'), array('%%', '%s'), $query);
            $query = vsprintf($query, $bindings);

            // Debug SQL queries
            echo "\n".$query."\n";
        });
    }

    /**
     * Soal nomor 1
     * @param Request $request
     * @param DisasterEventQueryBuilder $query
     * @return array
     */
    public function getDisasterEvents(Request $request,DisasterEventQueryBuilder $query)
    {
        if ($request->has("date")){
            $query->date($request->input('date'));
        }
        if ($request->has("year")){
            if ($request->has("month")){
                $query->month($request->input("year"),$request->input("month"));
            } else {
                $query->year($request->input("year"));
            }
        }
        //TODO continue period date, certain village, etc.
        $query->type("a type");
        $query->village(1);
        $query->subdistrict("a subdistrict");
        $query->district("a district");
        $query->province("a province");
        $query->select(["disaster_events.*"]);
        $data = $query->get();
        dd($data);
        return response()->json($data);
    }

    /**
     * Soal nomor 2
     * @param Request $request
     * @param DisasterEventQueryBuilder $query
     * @return array
     */
    public function getDisasterEventChanges(Request $request,DisasterEventQueryBuilder $query)
    {
        $query->joinWithDisasterAreas();
        $query->select([
            "disaster_events.*","disaster_areas.*",$this->db->raw("ST_AsGeoJSON(disaster_areas.region) AS region")
        ]);

        $data = $query->get();
        foreach ($data as $datum){
            $datum->region = GeoJson::jsonUnserialize(json_decode($datum->region));
        }
        dd($data);
        return response()->json($data);
    }

    /**
     * Soal nomor 3
     * @param $id
     * @param Request $request
     * @param VictimQueryBuilder $query
     * @return array
     */
    public function getVictimMovements($id,Request $request,VictimQueryBuilder $query)
    {
        $query->joinWithVictimLocations();
        $query->id($id);
        $query->orderBy('victim_locations.start','asc');
        $query->select(["victims.*",'victim_locations.start','victim_locations.end',$this->db->raw("ST_AsGeoJSON(victim_locations.point) AS point")]);
        $data = $query->get();
        foreach($data as $datum){
            $datum->point = GeoJson::jsonUnserialize(json_decode($datum->point));
        }
        dd($data);
        return response()->json($data);
    }

    /**
     * Soal nomor 4
     * @param Request $request
     * @param VillageQueryBuilder $query
     * @return array
     */
    public function getVillagesAffected(Request $request,VillageQueryBuilder $query)
    {
        $query->disasterEvent(1);
        $query->disasterType('flood');
        $query->month(2014,1);
        $data = $query->get();
        dd($data);
        return response()->json($data);
    }

    /**
     * Soal nomor 5
     * @param Request $request
     * @param VictimQueryBuilder $query
     * @return array
     */
    public function getVictims(Request $request,VictimQueryBuilder $query)
    {
        $query->disasterEvent(1);
        $query->disasterType('flood');
        $query->date('2014-10-31');
        $query->subdistrict('a subdistrict');
        $data = $query->get();
        dd($data);
        return response()->json($data);
    }

    /**
     * Soal nomor 6
     * @param Request $request
     * @param RefugeCampQueryBuilder $query
     * @return array
     */
    public function getRefugeCamps(Request $request,RefugeCampQueryBuilder $query)
    {
        echo $request->input("province")."\n";
        echo $request->input("district")."\n";
        echo $request->input("subdistrict")."\n";
        echo $request->input("village")."\n";
        $query->villageId($request->input("village"));
        $query->select(["refuge_camps.*",$this->db->raw("ST_AsGeoJSON(location) AS location")]);
        $data = $query->get();
        foreach ($data as $datum) {
            $datum->location = GeoJson::jsonUnserialize(json_decode($datum->location));
        }
        dd($data);
        return response()->json($data);
    }

    /**
     * Soal 7
     * @param Request $request
     * @param MedicalFacilityQueryBuilder $query
     * @return array
     */
    public function getMedicalFacilities(Request $request,MedicalFacilityQueryBuilder $query)
    {
        $query->villageId(10346);
        $query->select(["medical_facilities.*",$this->db->raw("ST_AsGeoJSON(location) AS location")])->distinct();
        $data = $query->get();
        foreach ($data as $datum){
            $datum->location = GeoJson::jsonUnserialize(json_decode($datum->location));
        }
        dd($data);
        return response()->json($data);
    }

    /**
     * Soal 8
     * @param Request $request
     * @param VictimQueryBuilder $query
     * @return int
     */
    public function getNumberOfVictims(Request $request,VictimQueryBuilder $query)
    {
//        $query->status('affected');
//        $query->ageGroup('baby');
//        $query->isFemale();
//        $query->refugeCamp('a refuge camp');
//        $query->medicalFacilityType('a type');
        $query->disasterEvent(1)->select(["victims.*"])->distinct();
        $data = $query->get();
        dd($data);
        return response()->json($query->count());
    }
}
