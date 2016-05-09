<?php

namespace App\Http\Controllers;


class InputController extends Controller
{
    public function getDraw(){
        return view('input.draw');
    }
}