<?php

namespace App\Services;

use App\Contracts\Dao\FirebaseTokenDaoInterface;
use App\Contracts\Services\FirebaseTokenServiceInterface;

class FirebaseTokenService implements FirebaseTokenServiceInterface
{
    private $firebaseTokenDao;

    /**
     * Class Constructor
     * @param firebaseTokenDaoInterface
     * @return
     */
    public function __construct(FirebaseTokenDaoInterface $firebaseTokenDao)
    {
        $this->firebaseTokenDao = $firebaseTokenDao;
    }

    /**
     * store firebase token
     *
     * @param  $data
     * @return void
     */
    public function tokenStore($data)
    {
        
        $search = $this->firebaseTokenDao->searchData($data->login_id);
        
        if ($search == null) {
            $createdata = [
                'login_id' => $data->login_id,
                'fcm_token' => $data->fcm_token,
            ];
            $createToken = $this->firebaseTokenDao->createToken($createdata);
            $message = config('constants.FIREBASE_TOKEN_CREATE');
            return [$message];
        } else {
            $updatedata = [
                'login_id' => $data->login_id,
                'fcm_token' => $data->fcm_token,
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $updateToken = $this->firebaseTokenDao->updateToken($updatedata, $data->login_id);
            $message = config('constants.FIREBASE_TOKEN_UPDATE');
            return [$message];
        }
    }
}
