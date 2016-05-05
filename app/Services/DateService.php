<?php

namespace App\Services;


interface DateService
{
    public function makeStartDateFromMonth($year,$month);

    public function makeStartDateFromYear($year);

    public function makeEndDateFromMonth($year,$month);

    public function makeEndDateFromYear($year);
}