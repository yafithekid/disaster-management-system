<?php

namespace App\Http\Controllers\Auth;

use App\Services\DisasterEventQueryBuilder;
use App\Services\MedicalFacilityQueryBuilder;
use App\Services\VictimQueryBuilder;
use App\User;
use Illuminate\Foundation\Application;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function test(Application $app){
        echo "nomor 7<br/>";
        /** @var MedicalFacilityQueryBuilder $medicalFacQB */
        $medicalFacQB = $app->make(MedicalFacilityQueryBuilder::class);
        $medicalFacQB->villageId(1)->subdistrict("woi")->district("aaa");
        echo $medicalFacQB->sql()."<br/>";
        echo "nomor 8<br/>";
        /** @var VictimQueryBuilder $victimQB */
        $victimQB = $app->make(VictimQueryBuilder::class);
        $victimQB->status('affected')->ageGroup('baby')->gender('f')->refugeCamp('aaa')->medicalFacilityType('woi');
        echo $victimQB->sql()."<br/>";
    }
}
