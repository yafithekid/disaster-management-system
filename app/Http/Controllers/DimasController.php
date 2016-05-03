<?php

namespace App\Http\Controllers;

use App\Services\DisasterEventQueryBuilder;
use App\Services\MedicalFacilityQueryBuilder;
use App\Services\RefugeCampQueryBuilder;
use App\Services\VictimQueryBuilder;
use App\Services\VillageQueryBuilder;
use Illuminate\Http\Request;

use App\Http\Requests;

class DimasController extends Controller
{
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
        echo $query->sql()."\n";
        return $query->get();
    }

    /**
     * Soal nomor 2
     * @param Request $request
     * @param DisasterEventQueryBuilder $query
     * @return array
     */
    public function getDisasterEventChanges(Request $request,DisasterEventQueryBuilder $query)
    {
        //TODO impl
        echo $query->type(null)->sql()."\n";
        return $query->get();
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
        $query->withVictimLocations();
        $query->id($id);
        echo $query->sql()."\n";
        return $query->get();
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
        echo $query->sql()."\n";
        return $query->get();
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
        echo $query->sql()."\n";
        return $query->get();
    }

    /**
     * Soal nomor 6
     * @param Request $request
     * @param RefugeCampQueryBuilder $query
     * @return array
     */
    public function getRefugeCamps(Request $request,RefugeCampQueryBuilder $query)
    {
        $query->villageId(1);
        echo $query->sql()."\n";
        return $query->get();
    }

    /**
     * Soal 7
     * @param Request $request
     * @param MedicalFacilityQueryBuilder $query
     * @return array
     */
    public function getMedicalFacilities(Request $request,MedicalFacilityQueryBuilder $query)
    {
        $query->villageId(1);
        $query->province('a province');
        $query->select(["medical_facilities.*"])->distinct();
        echo $query->sql()."\n";
        return $query->get();
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
        echo $query->sql()."\n";
        return $query->count();
    }
}
