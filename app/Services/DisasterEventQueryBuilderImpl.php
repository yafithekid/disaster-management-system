<?php

namespace App\Services;

use Illuminate\Database\Connection;

class DisasterEventQueryBuilderImpl implements DisasterEventQueryBuilder
{
    private $dateService;
    private $query;

    private $join_with_disasters = false;
    private $join_with_disaster_areas = false;
    private $join_with_villages = false;

    public function __construct(Connection $database,DateService $dateService)
    {
        $this->dateService = $dateService;
        $this->query = $database->table("disaster_events");
    }

    public function date($year, $month, $day)
    {
        $date = $this->dateService->makeCertainDate($year,$month, $day);
        return $this->periodDate($date,$date);
    }

    public function month($year, $month)
    {
        $input_date_start = $this->dateService->makeStartDateFromMonth($year,$month);
        $input_date_end = $this->dateService->makeEndDateFromMonth($year,$month);
        return $this->periodDate($input_date_start,$input_date_end);
    }

    public function year($year)
    {
        $input_date_start = $this->dateService->makeStartDateFromYear($year);
        $input_date_end = $this->dateService->makeEndDateFromYear($year);
        return $this->periodDate($input_date_start,$input_date_end);
    }

    public function periodDate($date_start, $date_end)
    {
        $this->query->whereRaw("(disaster_events.date_start,disaster_events.date_end) OVERLAPS (TIMESTAMP '$date_start', TIMESTAMP '$date_end')");
//        $this->query->where(function($q) use ($date_end,$date_start) {
//                $q->where("date_start",">=",$date_start)->where("date_start","<=",$date_end)
//            ->orWhere(function($q) use ($date_end,$date_start) {
//                $q->where("date_end",">=",$date_start)->where("date_end","<=",$date_end);
//            });
//        });
        return $this;
    }

    public function periodMonth($year_start, $month_start, $year_end, $month_end)
    {
        $date_start = $this->dateService->makeStartDateFromMonth($year_start,$month_start);
        $date_end = $this->dateService->makeEndDateFromMonth($year_end,$month_end);
        return $this->periodDate($date_start,$date_end);
    }

    public function periodYear($year_start, $year_end)
    {
        $date_start = $this->dateService->makeStartDateFromYear($year_start);
        $date_end = $this->dateService->makeEndDateFromYear($year_end);
        return $this->periodDate($date_start,$date_end);
    }

    public function subdistrict($subdistrict_name)
    {
        $this->joinWithDisasterAreas();
        $this->joinWithVillages();
        $this->distinct();
        $this->query->where("subdistrict","=",$subdistrict_name);
        return $this;
    }

    public function district($district_name)
    {
        $this->joinWithDisasterAreas();
        $this->joinWithVillages();
        $this->distinct();
        $this->query->where("district","=",$district_name);
        return $this;
    }

    public function province($province_name)
    {
        $this->joinWithDisasterAreas();
        $this->joinWithVillages();
        $this->distinct();
        $this->query->where("province","=",$province_name);
        return $this;
    }

    public function type($type)
    {
        $this->joinWithDisasters();
        if ($type != null){
            $this->query->where("disasters.type","=",$type);
        }
        return $this;
    }

    public function village($village_id)
    {
        $this->joinWithDisasterAreas();
        $this->joinWithVillages();
        $this->distinct();
        $this->query->where("villages.id","=",$village_id);
        return $this;
    }

    public function get()
    {
        return $this->query->get();
    }

    public function id($id)
    {
        $this->query->where("disaster_events.id","=",$id);
        return $this;
    }

    public function first()
    {
        return $this->query->first();
    }

    public function sql()
    {
        return $this->query->toSql();
    }

    public function joinWithDisasters(){
        if (!$this->join_with_disasters){
            $this->join_with_disasters = true;
            $this->query->join("disasters","disaster_events.id","=","disasters.disaster_event_id");
        }
    }

    public function joinWithDisasterAreas(){
        if (!$this->join_with_disaster_areas){
            $this->join_with_disaster_areas = true;
            $this->joinWithDisasters();
            $this->query->join("disaster_areas","disasters.id","=","disaster_areas.disaster_id");
        }
        return $this;
    }

    public function joinWithVillages(){
        if (!$this->join_with_villages){
            $this->join_with_villages = true;
            $this->joinWithDisasterAreas();
            $this->query->crossJoin("villages");
            $this->query->whereRaw("ST_Intersects(disaster_areas.region,villages.geom)");
        }
    }

    /**
     * @param array $array
     * @return DisasterEventQueryBuilder
     */
    public function select($array)
    {
        $this->query->select($array);
        return $this;
    }

    /**
     * @return DisasterEventQueryBuilder
     */
    public function distinct()
    {
        $this->query->distinct();
        return $this;
    }
    
    public function disasterType($type = null)
    {
        $this->joinWithDisasters();
        if ($type !== null){
            $this->query->where("disasters.type","=",$type);
        }
        return $this;
    }

    public function orderBy($column, $ord)
    {
        $this->query->orderBy($column,$ord);
        return $this;
    }

    /**
     * return the bindings
     * @return array
     */
    public function bindings()
    {
        return $this->query->getBindings();
    }

    public function periodFromString($period) {
        $startDate = $this->dateService->makeStartDateFromStringPeriod($period);
        $endDate = $this->dateService->makeEndDateFromStringPeriod($period);
        return $this->periodDate($startDate, $endDate);
    }

}