<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Barangay;
use App\Models\User;
use App\Models\Inventory;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
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
            'name' => ['required', 'string', 'max:255', 'alpha_spaces'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'region_psgc' => ['required', 'string', 'max:255'],
            'province_psgc' => ['required', 'string', 'max:255'],
            'city_psgc' => ['required', 'string', 'max:255'],
            'region' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'contact_no' => ['required', 'numeric', 'digits:11', 'unique:users', 'regex:/^(09)\d{9}$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {

        $user =  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'officer_type' => 'Administrator',
            'contact_no' => $data['contact_no'],
            'password' => Hash::make($data['password']),
        ]);
        Admin::create([
            'user_id' => $user->id,
            'region_psgc' =>  $data['region_psgc'],
            'province_psgc' =>  $data['province_psgc'],
            'city_psgc' =>  $data['city_psgc'],
            'address' => $data['city'] . ', ' . $data['province'] . ', ' . $data['region']
        ]);
        Inventory::create([
            'user_id' => $user->id,
            'name' => $data['city'] . ' Inventory'
        ]);
        $barangay = explode(",", $data['barangays'][0]);
        $data = [];
        foreach ($barangay as $index => $barangay_name) {
            $data[] = [
                'name' => $barangay[$index],
            ];
        }
        Barangay::insert($data);

        return $user;
    }
}