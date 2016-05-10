<?php

namespace App\Http\Controllers;

use Illuminate\Database\Connection;
use Illuminate\Http\Request;

use App\Http\Requests;

class DatafixController extends Controller
{
    public function reverseMedFac(Connection $database)
    {
        $areas = $database->table("medical_facilities")
            ->select(["id",$database->raw("ST_ASGeoJson(location) AS location")])
            ->get();
        foreach($areas as $area){
            $reversed = json_decode($area->location);
            $new_coordinate = [$reversed->coordinates[1],$reversed->coordinates[0]];
            $geojson = json_encode([
                "type" => "Point",
                "coordinates" => $new_coordinate
            ]);
            $database->table("medical_facilities")
                ->where("id","=",$area->id)
                ->update(["location" => $database->raw("ST_GeomFromGeoJSON('$geojson')")]);
        }
    }

    public function reverseRefCmp(Connection $database)
    {
        $areas = $database->table("refuge_camps")
            ->select(["id",$database->raw("ST_ASGeoJson(location) AS location")])
            ->get();
        foreach($areas as $area){
            $reversed = json_decode($area->location);
            $new_coordinate = [$reversed->coordinates[1],$reversed->coordinates[0]];
            $geojson = json_encode([
                "type" => "Point",
                "coordinates" => $new_coordinate
            ]);
            $database->table("refuge_camps")
                ->where("id","=",$area->id)
                ->update(["location" => $database->raw("ST_GeomFromGeoJSON('$geojson')")]);
        }
    }

    public function reverseVicLoc(Connection $database)
    {
        $areas = $database->table("victim_locations")
            ->select(["victim_id","start","end",$database->raw("ST_ASGeoJson(point) AS point")])
            ->get();
        foreach($areas as $area){
            $reversed = json_decode($area->point);
            $new_coordinate = [$reversed->coordinates[1],$reversed->coordinates[0]];
            $geojson = json_encode([
                "type" => "Point",
                "coordinates" => $new_coordinate
            ]);
            $database->table("victim_locations")
                ->where("victim_id","=",$area->victim_id)
                ->where("start","=",$area->start)
                ->where("end","=",$area->end)
                ->update(["point" => $database->raw("ST_GeomFromGeoJSON('$geojson')")]);
        }
    }


    public function reverseDisasterArea(Connection $database){
        $areas = $database->table("disaster_areas")->select(["disaster_id","start","end",$database->raw("ST_ASGeoJson(region) AS region")])->get();
        foreach($areas as $area){
            $reversed = json_decode($area->region);
            $new_coordinates = [];
            foreach($reversed->coordinates[0] as $coordinate){
                $a = $coordinate[0];
                $coordinate[0] = $coordinate[1];
                $coordinate[1] = $a;
                array_push($new_coordinates,$coordinate);
            }
            $geojson = json_encode([
                "type" => "Polygon",
                "coordinates" => [$new_coordinates]
            ]);
//            dd($geojson);
            $database->table("disaster_areas")->where("disaster_id","=",$area->disaster_id)
                ->where("start","=","'".$area->start."'")
                ->where("end","=","'".$area->end."'")
                ->update(["region" => $database->raw("ST_GeomFromGeoJSON('$geojson')")]);
        }
    }

    public function pushDisasterArea(Connection $database){
        $areas = $database->table("disaster_areas")->select(["disaster_id","start","end",$database->raw("ST_ASGeoJson(region) AS region")])->get();
        $i = 0;
        foreach($areas as $area){
            $reversed = json_decode($area->region);
            $new_coordinates = [];
            foreach($reversed->coordinates[0] as $coordinate){
                array_push($new_coordinates,$coordinate);
            }
            array_push($new_coordinates,$new_coordinates[0]);
            $geojson = json_encode([
                "type" => "Polygon",
                "coordinates" => [$new_coordinates]
            ]);
            if ($i == 0){
                $i++; continue;
            }
            $database->table("disaster_areas")->where("disaster_id","=",$area->disaster_id)
                ->where("start","=","'".$area->start."'")
                ->where("end","=","'".$area->end."'")
                ->update(["region" => $database->raw("ST_GeomFromGeoJSON('$geojson')")]);
        }
    }
}
