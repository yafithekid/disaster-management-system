<?php

namespace App\Services;

use Illuminate\Database\Connection;
use Illuminate\Log\Writer;

class DisasterEventQueryBuilderImpl implements DisasterEventQueryBuilder
{
    private $db;
    private $log;
    private $dateService;
    private $query;

    public function __construct(Connection $database,Writer $log,DateService $dateService)
    {
        $this->db = $database;
        $this->log = $log;
        $this->dateService = $dateService;
        $this->query = $this->db->table("disaster_events");
    }

    public function date($date)
    {
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
        $this->query->orWhere(function($q) use ($date_end,$date_start) {
                $q->where("date_start",">=",$date_start)->where("date_start","<=",$date_end);
            })
            ->orWhere(function($q) use ($date_end,$date_start) {
                $q->where("date_end",">=",$date_start)->where("date_end","<=",$date_end);
            });
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
        $this->query->where("subdistrict","=",$subdistrict_name);
        return $this;
    }

    public function district($district_name)
    {
        $this->query->where("district","=",$district_name);
        return $this;
    }

    public function province($province_name)
    {
        $this->query->where("province","=",$province_name);
    }

    public function type($type)
    {
        $this->query->join("disasters",function($join) use ($type) {
            $q = $join->on("disaster_events.id", "=", "disasters.id");
            if ($type !== null){
                $q->where("disasters.type", $type);
            }
        });
        return $this;
    }

    public function village($village_id)
    {
        $village = $this->db->table("villages")->where("id","=",$village_id)->first();
        $this->query->join("disasters",function($join) use ($village){
            $join->on("disaster_events.id","=","disasters.id")
                ->join("disaster_areas",function($daJoin) use ($village){
                    $daJoin->on("disaster_areas.disaster_id","=","disasters.id")
                        ->whereRaw("ST_Intersects(disaster_areas.area,".$village->geom.")");
                });
        });
        return $this;
    }

    public function get()
    {
        return $this->query->get();
    }

    public function id($id)
    {
        $this->query->where("id","=",$id);
        return $this;
    }

    public function first()
    {
        return $this->query->first();
    }

    public function sql()
    {
        return $this->query;
    }
    
}