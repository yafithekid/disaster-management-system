<?php

namespace App\Services;


class DateServiceImpl implements DateService
{

    public function makeStartDateFromMonth($year, $month)
    {
        return sprintf("%d-%d-1",$year,$month);
    }

    public function makeStartDateFromYear($year)
    {
        return $this->makeStartDateFromMonth($year,12);
    }

    public function makeEndDateFromMonth($year, $month)
    {
        $dom = 0;
        switch ($month){
            case 1: $dom = 31; break;
            case 2:
                $dom = ($this->is_leap_year($year))?29:28;
                break;
            case 3: $dom = 31; break;
            case 4: $dom = 30; break;
            case 5: $dom = 31; break;
            case 6: $dom = 30; break;
            case 7: $dom = 31; break;
            case 8: $dom = 31; break;
            case 9: $dom = 30; break;
            case 10: $dom = 31; break;
            case 11: $dom = 30; break;
            case 12: $dom = 31; break;
        }
        return sprintf("%d-%d-%d",$year,$month,$dom);
    }

    public function makeEndDateFromYear($year)
    {
        return $this->makeEndDateFromMonth($year,12);
    }

    function is_leap_year($year)
    {
        return ((($year % 4) == 0) && ((($year % 100) != 0) || (($year %400) == 0)));
    }
}