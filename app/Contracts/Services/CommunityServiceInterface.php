<?php

namespace App\Contracts\Services;

interface CommunityServiceInterface
{
    public function appCreateUser($userInfo);
    public function appUpdateUser($userInfo);
    public function appUserLogin($userInfo);
    public function appChangeLoginID($userInfo, $update_loginID);
    public function appCheckDuplicateUser($userInfo);
    public function searchCommunityUser($search);
    public function getcommunityUserListInCsv($search_csv);
    public function getCommunityUser($userNumber);
    public function updateCommunityUserStatus($userNumber, $statusObj);
    public function deleteCommunityUser($userNumber);
}
