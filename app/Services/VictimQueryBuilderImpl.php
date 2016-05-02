<?php

namespace App\Services;


use Illuminate\Database\Connection;

class VictimQueryBuilderImpl implements VictimQueryBuilder
{
    private $dateService;
    private $query;

    private $join_with_disaster_events = false;
    private $join_with_disasters = false;
    private $join_with_disaster_areas = false;
    private $join_with_victim_locations = false;
    private $join_with_victim_statuses = false;
    private $join_with_villages = false;
    private $join_with_medical_facilities = false;
    private $join_with_refuge_camps = false;


    public function __construct(Connection $database,DateService $dateService)
    {
        $this->dateService = $dateService;
        $this->query = $database->table("victims");
    }

    public function get()
    {
        return $this->query->get();
    }

    public function disasterEvent($id)
    {
        $this->joinWithVictimLocations();
        $this->joinWithDisasterEvents();
        $this->joinWithDisasters();
        $this->joinWithDisasterAreas();
        $this->query
            ->where("disaster_events.id","=",$id)
            ->whereRaw("ST_Within(victim_locations.point,disaster_areas.region)")
            ->whereRaw("(victim_locations.start,victim_locations.end) OVERLAPS (disaster_areas.start,disaster_areas.end)")
            ->select("victims.*")->distinct();
        return $this;
    }

    public function disasterType($type)
    {
        $this->joinWithDisasters();
        $this->query->where("disasters.type","=",$type);
        return $this;
    }

    public function date($date)
    {
        return $this->periodDate($date,$date);
    }

    public function periodDate($start_date,$end_date)
    {
        $this->joinWithDisasterAreas();
        $this->query
            ->whereRaw("(TIMESTAMP $start_date,TIMESTAMP $end_date) OVERLAPS (victim_locations.start, victim_locations.end)");
        return $this;
    }

    public function month($year, $month)
    {
        $start_date = $this->dateService->makeStartDateFromMonth($year,$month);
        $end_date = $this->dateService->makeEndDateFromMonth($year,$month);
        return $this->periodDate($start_date,$end_date);
    }

    public function year($year)
    {
        $start_date = $this->dateService->makeStartDateFromYear($year);
        $end_date = $this->dateService->makeEndDateFromYear($year);
        return $this->periodDate($start_date,$end_date);
    }

    public function villageId($village_id)
    {
        $this->query->where("village_id","=",$village_id);
        return $this;
    }

    public function count()
    {
        return $this->query->count();
    }

    public function status($status)
    {
        $this->joinWithVictimStatus();
        $this->query->where("victim_statuses.status","=",$status);
        return $this;
    }

    public function ageGroup($group)
    {
        switch (strtolower($group)) {
            case "baby":
                $this->query->where("date_of_birth", ">", "current_date - interval '5 years'");
                break;
            case "toddler":
                $this->query
                    ->where("date_of_birth", ">=", "current_date - interval '5 years'")
                    ->where("date_of_birth", "<=", "current_date - interval '1 years'");
                break;
            case "child":
                $this->query
                    ->where("date_of_birth", ">=", "current_date - interval '12 years'")
                    ->where("date_of_birth", "<=", "current_date - interval '5 years'");
                break;
            case "teenager":
                $this->query
                    ->where("date_of_birth", ">=", "current_date - interval '17 years'")
                    ->where("date_of_birth", "<=", "current_date - interval '13 years'");
                break;
            case "adult":
                $this->query
                    ->where("date_of_birth", ">=", "current_date - interval '59 years'")
                    ->where("date_of_birth", "<=", "current_date - interval '18 years'");
                break;
            case "elderly":
                $this->query
                    ->where("date_of_birth", "<=", "current_date - interval '60 years'");
                break;
        }
        return $this;
    }

    public function sql()
    {
        return $this->query->toSql();
    }

    public function subdistrict($subdistrict_name)
    {
        $this->joinWithVillages();
        $this->query->where("subdistrict","=",$subdistrict_name);
        return $this;
    }

    public function district($district_name)
    {
        $this->joinWithVillages();
        $this->query->where("district","=",$district_name);
        return $this;
    }

    public function province($province_name)
    {
        $this->joinWithVillages();
        $this->query->where("province","=",$province_name);
        return $this;
    }

    /**
     * @param $gender 'm' | 'f'
     * @return VictimQueryBuilder
     */
    public function gender($gender)
    {
        $this->query->where("gender","=",$gender);
        return $this;
    }

    /**
     * run the query and return first element found
     * @return object
     */
    public function first()
    {
        return $this->query->first();
    }

    /**
     * @param string $medical_facility_name
     * @return VillageQueryBuilder
     */
    public function medicalFacility($medical_facility_name)
    {
        $this->joinWithMedicalFacilties();
        $this->query->where("medical_facilities.name","=",$medical_facility_name);
        return $this;
    }

    /**
     * @param string $refuge_camp
     * @return VillageQueryBuilder
     */
    public function refugeCamp($refuge_camp)
    {
        $this->joinWithRefugeCamps();
        $this->query->where("refuge_camps.name","=",$refuge_camp);
        return $this;

    }

    /**
     * @param string $medical_facility_type
     * @return VillageQueryBuilder
     */
    public function medicalFacilityType($medical_facility_type)
    {
        $this->joinWithMedicalFacilties();
        $this->query->where("medical_facilities.type","=",$medical_facility_type);
        return $this;
    }

    /**
     * @param string $refuge_camp_type
     * @return VillageQueryBuilder
     */
    public function refugeCampType($refuge_camp_type)
    {
        $this->joinWithRefugeCamps();
        $this->query->where("refuge_camps.type","=",$refuge_camp_type);
        return $this;
    }

    private function joinWithVictimLocations(){
        if (!$this->join_with_victim_locations){
            $this->join_with_victim_locations = true;
            $this->query->join("victim_locations","victims.id","=","victim_locations.victim_id");
        }
    }

    private function joinWithVictimStatus(){
        if (!$this->join_with_victim_statuses){
            $this->join_with_victim_statuses = true;
            $this->query->join("victim_statuses","victims.id","=","victim_statuses.victim_id");
        }
    }

    private function joinWithDisasterEvents(){
        if (!$this->join_with_disaster_events){
            $this->join_with_disaster_events = true;
            $this->query->crossJoin("disaster_events");
        }
    }

    private function joinWithDisasters(){
        if (!$this->join_with_disasters){
            $this->joinWithDisasterEvents();
            $this->join_with_disasters = true;
            $this->query->join("disasters","disaster_events.id","=","disasters.disaster_event_id");
        }
    }

    private function joinWithDisasterAreas()
    {
        if (!$this->join_with_disaster_areas){
            $this->joinWithDisasters();
            $this->join_with_disaster_areas = true;
            $this->query->join("disaster_areas","disaster_areas.disaster_id","=","disasters.id");
        }
    }

    private function joinWithVillages(){
        if (!$this->join_with_villages){
            $this->join_with_villages = true;
            $this->query->join("villages","villages.id","=","victims.village_id");
        }
    }

    private function joinWithMedicalFacilties(){
        if (!$this->join_with_medical_facilities){
            $this->join_with_medical_facilities = true;
            $this->joinWithVictimLocations();
            $this->query
                ->crossJoin("medical_facilities")
                ->whereRaw("ST_Equals(medical_facilities.point,victim_locations.point)");
        }
    }

    private function joinWithRefugeCamps(){
        if (!$this->join_with_refuge_camps){
            $this->join_with_refuge_camps = true;
            $this->joinWithVictimLocations();
            $this->query
                ->crossJoin("refuge_camps")
                ->whereRaw("ST_Equals(refuge_camps.point,victim_locations.point)");
        }
    }



}