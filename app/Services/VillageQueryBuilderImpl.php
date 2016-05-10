<?php
/**
 * Created by IntelliJ IDEA.
 * User: yafi
 * Date: 02-May-16
 * Time: 10:22 PM
 */

namespace App\Services;


use Illuminate\Database\Connection;

class VillageQueryBuilderImpl implements VillageQueryBuilder
{

    private $dateService;
    private $query;

    private $join_with_disaster_events = false;
    private $join_with_disasters = false;
    private $join_with_disaster_areas = false;

    public function __construct(Connection $database, DateService $dateService)
    {
        $this->dateService = $dateService;
        $this->query = $database->table("villages");
    }

    /**
     * return result as SQL query
     * @return string
     */
    public function sql()
    {
        return $this->query->toSql();
    }

    /**
     * run the query and return as array
     * @return array
     */
    public function get()
    {
        return $this->query->get();
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
     * @param int $id
     * @return VillageQueryBuilder
     */
    public function disasterEvent($id)
    {
        $this->joinWithDisasterEvents();
        $this->query->where("disaster_events.id","=",$id);
        return $this;
    }

    /**
     * @param string $type
     * @return VillageQueryBuilder
     */
    public function disasterType($type)
    {
        $this->joinWithDisasters();
        $this->query->where("disasters.type","=",$type);
        return $this;
    }

    /**
     * @param string $date ex '2014-01-31'
     * @return VillageQueryBuilder
     */
    public function date($date)
    {
        $this->joinWithDisasterAreas();
        return $this->periodDate($date,$date);
    }

    /**
     * @param string $start_date '2014-01-31'
     * @param string $end_date '2014-01-31'
     * @return VillageQueryBuilder
     */
    public function periodDate($start_date, $end_date)
    {
        $this->joinWithDisasterAreas();
        $this->query
            ->whereRaw("(TIMESTAMP '$start_date',TIMESTAMP '$end_date') OVERLAPS (disaster_areas.start, disaster_areas.end)");
        return $this;
    }

    /**
     * @param int $year
     * @param int $month [1,12]
     * @return VillageQueryBuilder
     */
    public function month($year, $month)
    {
        $start_date = $this->dateService->makeStartDateFromMonth($year,$month);
        $end_date = $this->dateService->makeEndDateFromMonth($year,$month);
        return $this->periodDate($start_date,$end_date);
    }

    /**
     * @param int $year
     * @return VillageQueryBuilder
     */
    public function year($year)
    {
        $start_date = $this->dateService->makeStartDateFromYear($year);
        $end_date = $this->dateService->makeEndDateFromYear($year);
        return $this->periodDate($start_date,$end_date);
    }


    private function joinWithDisasterEvents()
    {
        if (!$this->join_with_disaster_events){
            $this->join_with_disaster_events = true;
            $this->joinWithDisasters();
            $this->query->join("disaster_events","disaster_events.id","=","disasters.disaster_event_id");
        }
    }

    private function joinWithDisasters()
    {
        if (!$this->join_with_disasters){
            $this->join_with_disasters = true;
            $this->joinWithDisasterAreas();
            $this->query->join("disasters","disaster_areas.disaster_id","=","disasters.id");
        }
    }

    public function joinWithDisasterAreas()
    {
        if (!$this->join_with_disaster_areas) {
            $this->join_with_disaster_areas = true;
            $this->query->crossJoin("disaster_areas")
                ->whereRaw("ST_Intersects(villages.geom,disaster_areas.region)");
        }
    }

    /**
     * @return VillageQueryBuilder
     */
    public function select($array)
    {
        $this->query->select($array);
        return $this;
    }

    /**
     * @return VillageQueryBuilder
     */
    public function distinct()
    {
        $this->query->distinct();
        return $this;
    }
}