<?php

namespace App\Dao;

use App\Contracts\Dao\FirebaseTokenDaoInterface;
use App\Models\FirebaseToken;

class FirebaseTokenDao implements FirebaseTokenDaoInterface
{
    /**
     * search data form database
     *
     * @param  $loginID
     * @return void
     */
    public function searchData($loginID)
    {
        return FirebaseToken::where('login_id', $loginID)->first();
    }

    /**
     * insert data to firebase table
     *
     * @param  $data
     * @return void
     */
    public function createToken($data)
    {
        return FirebaseToken::create($data);
    }

    /**
     * update data to firebase table
     *
     * @param  $data
     * @return void
     */
    public function updateToken($data, $loginId)
    {
        return FirebaseToken::where('login_id', $loginId)->update($data);
    }
}
