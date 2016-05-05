<?php

namespace App\Services;


interface RefugeCampQueryBuilder
{
    /**
     * @param array $array
     * @return RefugeCampQueryBuilder
     */
    public function select($array);

    /**
     * @return RefugeCampQueryBuilder
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
     * @param int $village_id
     * @return RefugeCampQueryBuilder
     */
    public function villageId($village_id);

    /**
     * @param string $subdistrict_name
     * @return RefugeCampQueryBuilder
     */
    public function subdistrict($subdistrict_name);

    /**
     * @param string $district_name
     * @return RefugeCampQueryBuilder
     */
    public function district($district_name);

    /**
     * @param string $province_name
     * @return RefugeCampQueryBuilder
     */
    public function province($province_name);
}