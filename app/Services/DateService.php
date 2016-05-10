<?php

namespace App\Services;


interface DateService
{
    public function makeStartDateFromMonth($year,$month);

    public function makeStartDateFromYear($year);

    public function makeEndDateFromMonth($year,$month);

    public function makeEndDateFromYear($year);

    public function makeCertainDate($year, $month, $day);

    public function makeStartDateFromStringPeriod($period);

    public function makeEndDateFromStringPeriod($period);
}