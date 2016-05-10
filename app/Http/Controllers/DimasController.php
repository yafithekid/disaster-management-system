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
use Illuminate\Http\RedirectResponse;

use App\Http\Requests;

class DimasController extends Controller
{
    private $db;
    const DEBUG = false;


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
        if ($request->has("disasterperiods")){
            $query->date($request->input('disasterperiods'));
        }
        if ($request->has("year")){
            if ($request->has("month")){
                $query->month($request->input("year"),$request->input("month"));
            } else {
                $query->year($request->input("year"));
            }
        }
        //TODO continue period date, certain village, etc.
        if ($request->has("disasterType")) {
            $query->type($request->input("disasterType"));
        }
        if ($request->has("province")) {
            $query->province($request->input("province"));
        }
        if ($request->has("district")) {
            $query->district($request->input("district"));
        }
        if ($request->has("subdistrict")) {
            $query->subdistrict($request->input("subdistrict"));
        }
        if ($request->has("village")) {
            $query->village($request->input("village"));
        }
        $query->select(["disaster_events.*"]);
        $data = $query->get();
        // dd($data);
        $retVal = [
            'resultSet' => $data,
            'executedQuery' => $query->toString()
        ];
        return response()->json($retVal);
    }

    /**
     * Soal nomor 2
     * @param Request $request
     * @param DisasterEventQueryBuilder $query
     * @return array
     */
    public function getDisasterEventChanges(Request $request,DisasterEventQueryBuilder $query)
    {
        $query->joinWithDisasterAreas()->orderBy("disaster_areas.start","asc");
        $query->id($request->input("id"));
        $query->select([
            $this->db->raw("ST_AsGeoJSON(ST_Centroid(disaster_areas.region)) AS centroid"),
            "disaster_events.*","disaster_areas.*",$this->db->raw("ST_AsGeoJSON(disaster_areas.region) AS region")
        ]);
        $data = $query->get();

        foreach ($data as $datum){
            $datum->centroid = GeoJson::jsonUnserialize(json_decode($datum->centroid));
            $datum->region = GeoJson::jsonUnserialize(json_decode($datum->region));
        }
        return response()->json([
            'resultSet' => $data,
            'executedQuery' => $this->createSQLRawQuery($query->sql(),$query->bindings())
        ]);
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
        // dd($data);
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
//        $query->disasterType('flood');
//        $query->month(2014,1);
//        dd($query->sql());
        $query->select(["villages.*"]);
        $query->distinct();
        $data = $query->get();
//        dd($data);
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
        echo $request;
        $query->villageId($request->input("village"));
        $query->select(["refuge_camps.*",$this->db->raw("ST_AsGeoJSON(location, 4326) AS location")]);
        $data = $query->get();
        $features = array();
        foreach ($data as $datum) {
            $datum->location = GeoJson::jsonUnserialize(json_decode($datum->location));
            $features[] = array(
                    'type' => 'Feature',
                    'geometry' => $datum->location,
                    'properties' => array('name' => $datum->name, 'capacity' => $datum->capacity, 'type' => $datum->type),
                    );
        }
        // dd($data);
        // $response = response()->json($data);

        //constructing geojson object
        // $original_data = json_decode($response, true);
        // foreach($response->content() as $key => $value) { 
        //     $features[] = array(
        //             'type' => 'Feature',
        //             'geometry' => $value['location'],
        //             'properties' => array('name' => $value['name'], 'id' => $value['id'], 'capacity' => $value['capacity'], 'type' => $value['type']),
        //             );
        //     };   

        $allfeatures = array('type' => 'FeatureCollection', 'features' => $features);
        $response = response()->json($allfeatures);
        return redirect()->route('index')->with('response', $response);
    }

    /**
     * Soal 7
     * @param Request $request
     * @param MedicalFacilityQueryBuilder $query
     * @return array
     */
    public function getMedicalFacilities(Request $request,MedicalFacilityQueryBuilder $query)
    {
        $query->villageId($request->input('villageId'));
        $query->select(["medical_facilities.*",$this->db->raw("ST_AsGeoJSON(location) AS location")])->distinct();
        $data = $query->get();
        foreach ($data as $datum){
            $datum->location = GeoJson::jsonUnserialize(json_decode($datum->location));
        }
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

    private function createSQLRawQuery($query,$bindings){
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

        return $query;
    }
}
