<?php

namespace App\Contracts\Services;

interface UserRegisterServiceInterface
{
    public function userRegister($data);
    public function userLogin($loginData);
    public function appRegister($registerData);
}
