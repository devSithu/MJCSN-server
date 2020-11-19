<?php

namespace App\Services;

use App\Contracts\Dao\CommunityDaoInterface;
use App\Contracts\Services\CommunityServiceInterface;
use CpsCSV;
use DB;

class CommunityService implements CommunityServiceInterface
{
    private $communityDao;

    /**
     * Class Constructor
     * @param CommunityDaoInterface
     * @return
     */
    public function __construct(CommunityDaoInterface $communityDao)
    {
        $this->communityDao = $communityDao;
    }

    /**
     * Create user function
     *
     * @param [type] $userInfo
     * @return void
     */
    public function appCreateUser($userInfo)
    {
        $userDataArray = array();
        $userArr = $this->createUserArr($userInfo);
        $user = $this->communityDao->getUserInfo($userInfo);
        if (!is_null($user)) {
            DB::beginTransaction();
            $newUser = $this->communityDao->createUser($userArr);
            $introducerArr = $this->createIntroducerArr($newUser->user_number, $userInfo->connect_sns);
            $introducer = $this->communityDao->createIntroducer($introducerArr);
            DB::commit();
        } else {
            $newUser = $this->communityDao->createUser($userArr);
        }
        $userDataArray['token'] = $newUser->createToken('community_client')->accessToken;
        $userDataArray['login_id'] = $newUser->login_id;

        return $userDataArray;
    }

    /**
     * update user for mobile
     *
     * @param [type] $userInfo
     * @return void
     */
    public function appUpdateUser($userInfo)
    {
        $userUpdateArr = $this->updateUserArr($userInfo);
        return $this->communityDao->appUpdateUser($userUpdateArr, $userInfo->login_id);
    }

    /**
     * login user for mobile
     *
     * @param [type] $userInfo
     * @return void
     */
    public function appUserLogin($userInfo)
    {
        $userDataArray = array();
        $userData = $this->communityDao->appUserLogin($userInfo);
    
        if ($userInfo->login_id == $userData->login_id) {
            $token = $userData->createToken('community_client')->accessToken;
            $userDataArray['statVal'] = 1;
            $userDataArray['user_name'] = $userData->user_name;
            $userDataArray['token'] = $token;
            return $userDataArray;
        } else {
            $userDataArray['statVal'] = 0;
            return $userInfo;
        }
    }

    /**
     * change user loginID for mobile
     *
     * @param [type] $userInfo
     * @return void
     */
    public function appChangeLoginID($userInfo, $update_loginID)
    {
        return $this->communityDao->appChangeLoginID($userInfo, $update_loginID);
    }

    /**
     * check user duplicate for mobile
     *
     * @param [type] $userInfo
     * @return void
     */
    public function appCheckDuplicateUser($userInfo)
    {
        return $this->communityDao->appCheckDuplicateUser($userInfo);
    }

    /**
     * Search function
     *
     * @param [type] $search
     * @return void
     */
    public function searchCommunityUser($search)
    {
        return $this->communityDao->searchCommunityUser($search);
    }

    /**
     * Download acommunity user csv.
     *
     * @return void
     */
    public function getcommunityUserListInCsv($search_csv)
    {
        $communityusers = $this->communityDao->getCommunityUserForCsv($search_csv);
        $columns = $this->prepareColumns();
        $csv = CpsCSV::toLineFromArray($columns->pluck('label')->all(), 'header', []);
        foreach ($communityusers as $user) {
            $arr = [];
            foreach ($columns as $c) {
                $arr[] = CpsCSV::toCell($user[$c['value']], false);
            }
            $csv .= implode(',', $arr) . "\n";
        }

        return $csv;
    }

    /**
     * Get community detail function
     *
     * @param [type] $userNumber
     * @return void
     */
    public function getCommunityUser($userNumber)
    {
        return $this->communityDao->getCommunityUser($userNumber);
    }

    /**
     * Update community user status function
     *
     * @return void
     */
    public function updateCommunityUserStatus($userNumber, $statusObj)
    {
        $user = [
            'status' => $statusObj->status,
        ];
        return $this->communityDao->updateCommunityUserStatus($userNumber, $user);
    }

    /**
     * Delete community user function
     *
     * @param [type] $userNumber
     * @return void
     */
    public function deleteCommunityUser($userNumber)
    {
        $this->communityDao->deleteCommunityUser($userNumber);
    }



    /**
     * Create User Array
     *
     * @param [type] $userInfo
     * @return void
     */
    private function createUserArr($userInfo)
    {
        $userDataArr = [
            'login_id' => $userInfo->login_id,
            'password' => $userInfo->password,
            'user_type' => $userInfo->user_type,
            'user_name' => $userInfo->user_name,
            'gender' => $userInfo->gender,
            'date_of_birth' => date('Y-m-d', strtotime($userInfo->date_of_birth)),
            'nrc_number' => $userInfo->nrc_number,
            'graduated_from' => strpos($userInfo->graduated_from, 'Other') !== false ? $userInfo->graduated_from : explode("/", $userInfo->graduated_from)[0],
            'graduated_dep' => $userInfo->graduated_dep,
            'graduated_year' => $userInfo->graduated_year,
            'region' => $userInfo->region,
            'address' => $userInfo->address,
            'phone_number' => $userInfo->phone_number,
            'email' => $userInfo->email,
            'career' => $userInfo->career,
            'status' => $userInfo->status,
            'connect_sns' => $userInfo->connect_sns,
            'nrc_image' => $userInfo->nrc_image,
            'answer_one' => $userInfo->answer_one,
            'answer_two' => $userInfo->answer_two,
            'answer_three' => $userInfo->answer_three,
            'answer_four' => $userInfo->answer_four,
        ];
        return $userDataArr;
    }

    /**
     * Create User Array
     *
     * @param [type] $userInfo
     * @return void
     */
    private function updateUserArr($userInfo)
    {
        $userUpdateArr = [
            'password' => $userInfo->password,
            'user_type' => $userInfo->user_type,
            'user_name' => $userInfo->user_name,
            'gender' => $userInfo->gender,
            'date_of_birth' => date('Y-m-d', strtotime($userInfo->date_of_birth)),
            'nrc_number' => $userInfo->nrc_number,
            'graduated_from' => strpos($userInfo->graduated_from, 'Other') !== false ? $userInfo->graduated_from : explode("/", $userInfo->graduated_from)[0],
            'graduated_dep' => $userInfo->graduated_dep,
            'graduated_year' => $userInfo->graduated_year,
            'region' => $userInfo->region,
            'career' => $userInfo->career,
            'status' => $userInfo->status,
            'address' => $userInfo->address,
            'phone_number' => $userInfo->phone_number,
            'email' => $userInfo->email,
            'connect_sns' => $userInfo->connect_sns,
            'nrc_image' => $userInfo->nrc_image,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        return $userUpdateArr;
    }

    /**
     * create Introducer Array
     *
     * @param [type] $user
     * @param [type] $userInfo
     * @return void
     */
    private function createIntroducerArr($userNumber, $connectSNS)
    {
        $introducerArr = [
            'user_number' => $userNumber,
            'introduced_number' => $connectSNS,
            'charge_code' => '',
            'status' => '0',
        ];
        return $introducerArr;
    }

    /**
     * Columns for user.
     */
    private function prepareColumns()
    {
        $columns = collect([
            ['label' => 'user_number', 'value' => 'user_number'],
            ['label' => 'user_name', 'value' => 'user_name'],
            ['label' => 'gender', 'value' => 'gender'],
            ['label' => 'date_of_birth', 'value' => 'date_of_birth'],
            ['label' => 'nrc_number', 'value' => 'nrc_number'],
            ['label' => 'graduated_from', 'value' => 'graduated_from'],
            ['label' => 'graduated_dep', 'value' => 'graduated_dep'],
            ['label' => 'graduated_year', 'value' => 'graduated_year'],
            ['label' => 'address', 'value' => 'address'],
            ['label' => 'phone_number', 'value' => 'phone_number'],
            ['label' => 'email', 'value' => 'email'],
            ['label' => 'status', 'value' => 'status'],
        ]);

        return $columns;
    }
}
