<?php

namespace App\Contracts\Dao;

interface FirebaseTokenDaoInterface
{
    public function searchData($loginID);
    public function createToken($data);
    public function updateToken($data, $loginId);
}
