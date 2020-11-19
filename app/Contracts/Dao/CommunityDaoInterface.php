<?php

namespace App\Contracts\Dao;

interface CommunityDaoInterface
{
    public function getUserInfo($userInfo);
    public function createUser($userArr);
    public function createIntroducer($introducerArr);
    public function appUpdateUser($userUpdateArr, $login_id);
    public function appUserLogin($userInfo);
    public function appChangeLoginID($userInfo, $update_loginID);
    public function appCheckDuplicateUser($userInfo);
    public function searchCommunityUser($search);
    public function getCommunityUserForCsv($search_csv);
    public function getCommunityUser($userNumber);
    public function updateCommunityUserStatus($userNumber, $user);
    public function deleteCommunityUser($userNumber);
    public function changeUserNumber($id);
}
