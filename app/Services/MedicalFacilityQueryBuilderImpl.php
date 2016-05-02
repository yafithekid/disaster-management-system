<?php

namespace App\Services;


use Illuminate\Database\Connection;

class MedicalFacilityQueryBuilderImpl implements MedicalFacilityQueryBuilder
{
    private $query;

    private $join_with_villages = false;

    public function __construct(Connection $database)
    {
        $this->query = $database->table("medical_facilities");
    }

    public function villageId($village_id)
    {
        $this->joinWithVillages();
        $this->query->where("villages.id","=",$village_id);
        return $this;
    }

    public function subdistrict($subdistrict_name)
    {
        $this->joinWithVillages();
        $this->query->where("villages.subdistrict","=",$subdistrict_name);
        return $this;
    }

    public function district($district_name)
    {
        $this->joinWithVillages();
        $this->query->where("villages.district","=",$district_name);
        return $this;
    }

    public function province($province_name)
    {
        $this->joinWithVillages();
        $this->query->where("villages.province","=",$province_name);
        return $this;
    }

    private function joinWithVillages(){
        if (!$this->join_with_villages){
            $this->join_with_villages = true;
            $this->query->join("villages","medical_facilities.village_id","=","villages.id");
        }
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
}