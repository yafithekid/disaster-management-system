<?php

namespace App\Services;


interface VillageQueryBuilder
{
    /**
     * @param array $array
     * @return VillageQueryBuilder
     */
    public function select($array);

    /**
     * @return VillageQueryBuilder
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
     * @param int $id
     * @return VillageQueryBuilder
     */
    public function disasterEvent($id);

    /**
     * @param string $type
     * @return VillageQueryBuilder
     */
    public function disasterType($type);

    /**
     * @param string $date ex '2014-01-31'
     * @return VillageQueryBuilder
     */
    public function date($date);

    /**
     * @param string $start_date '2014-01-31'
     * @param string $end_date '2014-01-31'
     * @return VillageQueryBuilder
     */
    public function periodDate($start_date,$end_date);

    /**
     * @param int $year
     * @param int $month [1,12]
     * @return VillageQueryBuilder
     */
    public function month($year,$month);

    /**
     * @param int $year
     * @return VillageQueryBuilder
     */
    public function year($year);

}