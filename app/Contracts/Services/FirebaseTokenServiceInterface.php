<?php

namespace App\Contracts\Services;

interface FirebaseTokenServiceInterface
{
    public function tokenStore($data);
}
