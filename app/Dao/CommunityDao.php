<?php

namespace App\Dao;

use App\Contracts\Dao\CommunityDaoInterface;
use App\Models\CommunityUser;
use App\Models\Introducer;
use Illuminate\Support\Facades\Input;

class CommunityDao implements CommunityDaoInterface
{
    /**
     * User Info function
     *
     * @param [type] $userInfo
     * @return void
     */
    public function getUserInfo($userInfo)
    {
        $result = CommunityUser::where('login_id', $userInfo->connect_sns)->first();
        return $result;
    }

    /**
     * Create user function
     *
     * @param [type] $userArr
     * @return void
     */
    public function createUser($userArr)
    {
        return CommunityUser::create($userArr);
    }

     /**
     * change user number data
     *
     * @param $id 
     * @return void
     */
    public function changeUserNumber($id)
    {
        return CommunityUser::where('login_id',$id)->get('user_number');
    }

    /**
     * Create introducer function
     *
     * @param [type] $introducerArr
     * @return void
     */
    public function createIntroducer($introducerArr)
    {
        Introducer::create($introducerArr);
    }

    /**
     * update user for mobile
     *
     * @param [type] $userInfo
     * @return void
     */
    public function appUpdateUser($userUpdateArr, $login_id)
    {
        return CommunityUser::where('login_id', $login_id)->update($userUpdateArr);
    }

    /**
     * User Login for mobile
     *
     * @param $userInfo
     * @return void
     */
    public function appUserLogin($userInfo)
    {
        $userData = CommunityUser::where('login_id', $userInfo->login_id)
            ->select('user_number', 'login_id', 'user_name')
            ->first();
        return $userData;
    }

    /**
     * change user loginID for mobile
     *
     * @param [type] $userInfo
     * @return void
     */
    public function appChangeLoginID($userInfo, $update_loginID)
    {
        return CommunityUser::where('user_number', $userInfo->user_number)
            ->update(['login_id' => $update_loginID]);
    }

    /**
     * Check Register User for mobile
     *
     * @param $userInfo
     * @return void
     */
    public function appCheckDuplicateUser($userInfo)
    {
        $userDataArry = array();
        if (!empty($userInfo->email)) {
            if (CommunityUser::where('email', '=', Input::get('email'))->exists()) {
                $userDataArry['email'] = $userInfo->email;
            }
        }
        // if (CommunityUser::where('user_name', '=', Input::get('user_name'))->exists()) {
        //     $userDataArry['user_name'] = $userInfo->user_name;
        // }
        // if (CommunityUser::where('nrc_number', '=', Input::get('nrc_number'))->exists()) {
        //     $userDataArry['nrc_number'] = $userInfo->nrc_number;
        // }
        return $userDataArry;
    }

    /**
     * Search function
     *
     * @param [type] $search
     * @return void
     */
    public function searchCommunityUser($search)
    {
        
        $communityusers = $this->selectCommunityUsers($search);

        return $communityusers->orderBy('user_number')->paginate(config('constants.PAGINATION'));
    }

    /**
     * Download csv function
     *
     * @param [type] $search_csv
     * @return void
     */
    public function getCommunityUserForCsv($search_csv)
    {   
        $communityusers = $this->selectCommunityUsers($search_csv);

        return $communityusers->orderBy('user_number')->get();
    }

    /**
     * Get community detail function
     *
     * @param [type] $userNumber
     * @return void
     */
    public function getCommunityUser($userNumber)
    {
        return CommunityUser::where('user_number', $userNumber)->first();
    }

    /**
     * Update community user status function
     *
     * @return void
     */
    public function updateCommunityUserStatus($userNumber, $user)
    {
        $communityuser = CommunityUser::findOrFail($userNumber)->update($user);
        return $communityuser;
    }

    /**
     * Delete community user function
     *
     * @param [type] $userNumber
     * @return void
     */
    public function deleteCommunityUser($userNumber)
    {
        CommunityUser::findOrFail($userNumber)->delete();
    }


    /**
     * Query for search
     *
     * @param [type] $search
     * @return void
     */
    private function selectCommunityUsers($search)
    {
        $communityusers = CommunityUser::select('*');
        
        if (!empty($search->user_name)) {
            $communityusers = $communityusers->where('user_name', 'LIKE', '%' . $search->user_name . '%');
        }
        if (!empty($search->gender)) {
            $communityusers = $communityusers->where('gender', 'LIKE', '%' . $search->gender . '%');
        }

        if (!empty($search->fromDate) && is_null($search->toDate)) {
            $communityusers = $communityusers->whereDate('date_of_birth', '>=', $search->fromDate);
        } elseif (!empty($search->toDate) && is_null($search->fromDate)) {
            $communityusers = $communityusers->whereDate('date_of_birth', '<=', $search->toDate);
        } elseif (!empty($search->fromDate) && !empty($search->toDate)) {
            $communityusers = $communityusers->whereDate('date_of_birth', '>=', $search->fromDate);
            $communityusers = $communityusers->whereDate('date_of_birth', '<=', $search->toDate);
        }
        if (!empty($search->registerFromDate) && is_null($search->registerToDate)) {
            $communityusers = $communityusers->whereDate('created_at', '>=', $search->registerFromDate);
        } elseif (!empty($search->registerToDate) && is_null($search->registerFromDate)) {
            $communityusers = $communityusers->whereDate('created_at', '<=', $search->registerToDate);
        } elseif (!empty($search->registerFromDate) && !empty($search->registerToDate)) {
            $communityusers = $communityusers->whereDate('created_at', '>=', $search->registerFromDate);
            $communityusers = $communityusers->whereDate('created_at', '<=', $search->registerToDate);
        }

        // if (!empty($search->nrc_number)) {
        //     $communityusers = $communityusers->where('nrc_number', 'LIKE', '%' . $search->nrc_number . '%');
        // }

        if (!empty($search->graduated_from)) {
            $communityusers = $communityusers->where('graduated_from', 'LIKE', '%' . $search->graduated_from . '%');
        }
        if (!empty($search->graduated_dep)) {
            $communityusers = $communityusers->where('graduated_dep', 'LIKE', '%' . $search->graduated_dep . '%');
        }
        if (!empty($search->graduated_year)) {
            $communityusers = $communityusers->where('graduated_year', 'LIKE', '%' . $search->graduated_year . '%');
        }

        // if (!empty($search->address)) {
        //     $communityusers = $communityusers->where('address', 'LIKE', '%' . $search->address . '%');
        // }
        if (!empty($search->phone_number)) {
            $communityusers = $communityusers->where('phone_number', 'LIKE', '%' . $search->phone_number . '%');
        }

        if (!empty($search->email)) {
            $communityusers = $communityusers->where('email', 'LIKE', '%' . $search->email . '%');
        }

        if (!is_null($search->status)) {
            $communityusers = $communityusers->where('status', 'LIKE', '%' . $search->status . '%');
        }
        return $communityusers;
    }
}
