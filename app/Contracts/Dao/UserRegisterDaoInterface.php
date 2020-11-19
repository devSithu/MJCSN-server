<?php

namespace App\Contracts\Dao;

interface UserRegisterDaoInterface
{
    public function userRegister($data);
    public function userLogin($loginData);
    public function appRegister($registerData);
}
