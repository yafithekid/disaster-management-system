<?php

namespace App\Services;
interface DisasterEventQueryBuilder
{
    /**
     * join dengan disaster areas
     * @return DisasterEventQueryBuilder
     */
    public function joinWithDisasterAreas();

    /**
     * join dengan disasters
     * @return DisasterEventQueryBuilder
     */
    public function joinWithDisasters();

    /**
     * @param string|null $type
     * @return DisasterEventQueryBuilder
     */
    public function disasterType($type = null);

    /**
     * @param array $array
     * @return DisasterEventQueryBuilder
     */
    public function select($array);

    /**
     * @return DisasterEventQueryBuilder
     */
    public function distinct();

    /**
     * return result as SQL query
     * @return string
     */
    public function sql();

    /**
     * run the query and return as array
     * @return array
     */
    public function get();

    /**
     * run the query and return first element found
     * @return object
     */
    public function first();


    /**
     * filter based on primary key
     * @param int $id
     * @return DisasterEventQueryBuilder
     */
    public function id($id);

    /**
     * @param string $subdistrict_name
     * @return DisasterEventQueryBuilder
     */
    public function subdistrict($subdistrict_name);

    /**
     * @param string $district_name
     * @return DisasterEventQueryBuilder
     */
    public function district($district_name);

    /**
     * @param string $province_name
     * @return DisasterEventQueryBuilder
     */
    public function province($province_name);

    /**
     * join dengan tabel 'disasters'. jika type tidak null, akan ada tambahan where
     * @param string|null $type
     * @return DisasterEventQueryBuilder
     */
    public function type($type);

    /**
     * @param int $village_id
     * @return DisasterEventQueryBuilder
     */
    public function village($village_id);

    /**
     * @param string $date example '2014-01-31'
     * @return DisasterEventQueryBuilder
     */
    public function date($date);

    /**
     * @param int $year
     * @param int $month [1,12]
     * @return DisasterEventQueryBuilder
     */
    public function month($year, $month);

    /**
     * @param int $year
     * @return DisasterEventQueryBuilder
     */
    public function year($year);

    /**
     * @param string $date_start example '2014-01-31'
     * @param string $date_end example '2014-01-31'
     * @return DisasterEventQueryBuilder
     */
    public function periodDate($date_start, $date_end);

    /**
     * @param int $year_start
     * @param int $month_start [1,12]
     * @param int $year_end
     * @param int $month_end [1,12]
     * @return DisasterEventQueryBuilder
     */
    public function periodMonth($year_start, $month_start, $year_end, $month_end);

    /**
     * @param int $year_start
     * @param int $year_end
     * @return DisasterEventQueryBuilder
     */
    public function periodYear($year_start, $year_end);

}