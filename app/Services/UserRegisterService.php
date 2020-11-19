<?php

namespace App\Services;

use App\Contracts\Dao\UserRegisterDaoInterface;
use App\Contracts\Services\UserRegisterServiceInterface;
use Hash;

class UserRegisterService implements UserRegisterServiceInterface
{
    private $userRegisterDao;

    /**
     * Class Constructor
     * @param userRegisterDaoInterface
     * @return
     */
    public function __construct(UserRegisterDaoInterface $userRegisterDao)
    {
        $this->userRegisterDao = $userRegisterDao;
    }

    /**
     * register for web
     *
     * @param [type] $data
     * @return void
     */
    public function userRegister($data)
    {
        $data = [
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
        ];
        return $this->userRegisterDao->userRegister($data);
    }

    /**
     * login for web
     *
     * @param [type] $loginData
     * @return void
     */
    public function userLogin($loginData)
    {
        return $this->userRegisterDao->userLogin($loginData);
    }

    //api register
    public function appRegister($registerData)
    {
        $userDataArray = array();
        $userData = $this->userRegisterDao->appRegister($registerData);
        $userDataArray['token'] = $userData->createToken('community_register')->accessToken;
        $userDataArray['login_id'] = $userData->login_id;

        return $userDataArray;
        // return $this->userRegisterDao->appRegister();
    }

}
