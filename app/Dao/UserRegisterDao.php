<?php

namespace App\Dao;

use App\Contracts\Dao\UserRegisterDaoInterface;
use App\Models\RegisterUser;
use Illuminate\Support\Facades\Hash;

class UserRegisterDao implements UserRegisterDaoInterface
{
    /**
     * create register for web
     *
     * @param [type] $data
     * @return void
     */
    public function userRegister($data)
    {
        return RegisterUser::create($data);
    }

    /**
     * login for web
     *
     * @param [type] $login_data
     * @return void
     */
    public function userLogin($loginData)
    {
        return RegisterUser::where('email', $loginData->email)->first();
    }

    //api register

    public function appRegister($registerData)
    {
        $userData = RegisterUser::create([
            'name' => $registerData->name,
            'email' => $registerData->email,
            'password' => Hash::make($request->password),
            'created_at' => DB::raw('CURRENT_TIMESTAMP'),
            'updated_at' => DB::raw('CURRENT_TIMESTAMP'),
        ]);
        return $userData;
    }
}
