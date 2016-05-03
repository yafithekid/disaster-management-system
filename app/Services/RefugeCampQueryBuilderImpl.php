<?php

namespace App\Services;


use Illuminate\Database\Connection;

class RefugeCampQueryBuilderImpl implements RefugeCampQueryBuilder
{
    private $query;
    private $join_with_villages;

    public function __construct(Connection $database)
    {
        $this->query = $database->table("refuge_camps");
    }

    public function villageId($village_id)
    {
        $this->joinWithVillages();
        $this->query->where("villages.id","=",$village_id);
        // TODO: Implement villageId() method.
    }

    public function subdistrict($subdistrict_name)
    {
        $this->joinWithVillages();
        $this->query->where("villages.subdistrict","=",$subdistrict_name);
    }

    public function district($district_name)
    {
        $this->joinWithVillages();
        $this->query->where("villages.district","=",$district_name);
    }

    public function province($province_name)
    {
        $this->joinWithVillages();
        $this->query->where("villages.province","=",$province_name);
    }

    private function joinWithVillages()
    {
        if (!$this->join_with_villages){
            $this->join_with_villages = true;
            $this->query->join("villages","refuge_camps.village_id","=","villages.id");
        }
    }

    public function sql()
    {
        return $this->query->toSql();
    }

    public function get()
    {
        return $this->query->get();
    }

    public function first()
    {
        return $this->query->first();
    }

    /**
     * @param array $array
     * @return RefugeCampQueryBuilder
     */
    public function select($array)
    {
        $this->query->select($array);
        return $this;
    }

    /**
     * @return RefugeCampQueryBuilder
     */
    public function distinct()
    {
        $this->query->distinct();
        return $this;
    }
}