<?php

namespace App\Dao;

use App\Contracts\Dao\BillPaymentDaoInterface;
use App\Models\CommunityUser;
use App\Models\Introducer;
use Illuminate\Support\Facades\DB;

class BillPaymentDao implements BillPaymentDaoInterface
{
    /**
     * pay bill for connect person
     *
     * @param -
     * @return void
     */
    public function billPayment()
    {
        $users = Introducer::select('introducer.introduced_number as login_id', 'community_user.user_name', 'email', 'phone_number', 'career')
            ->selectRaw('COUNT(introducer.introduced_number) as count_number')
            ->join('community_user', 'introducer.introduced_number', 'community_user.login_id')
            ->where('introducer.status', '<', 2)
            ->orderBy('community_user.user_number', 'desc')
            ->groupBy('introducer.introduced_number')
            ->paginate(config('constants.PAGINATION'));
        return $users;
    }

    /**
     * search with login id and return their data
     *
     * @param [integer] $login_id
     * @return void
     */
    public function getIntroducedUsers($loginId)
    {
        $userData = DB::table('introducer')
            ->join('community_user', function ($join) use ($loginId) {
                $join->on('introducer.user_number', '=', 'community_user.user_number')
                    ->where('introducer.introduced_number', '=', $loginId);
            })
            ->orderBy('community_user.user_number', 'desc')
            ->select('community_user.*', 'introducer.*', 'community_user.user_number as community_user_number', 'introducer.user_number as introducer_user_number')
            ->get();

        return $userData;
    }

    /**
     * get user data
     *
     * @param [integer] $loginId
     * @return void
     */
    public function getUserData($loginId)
    {
        return CommunityUser::where('login_id', $loginId)->first();
    }

    /**
     * update introducer user phone bill
     *
     * @param [integer] $phoneBill
     * @return void
     */
    public function updatePhoneBill($number, $phoneBill)
    {
        Introducer::where('user_number', $number)
            ->update(['charge_code' => $phoneBill, 'updated_at' => DB::raw('CURRENT_TIMESTAMP')]);
    }

    /**
     * delete introduce user phone bill number
     *
     * @param [integer] $id
     * @return void
     */
    public function deleteBill($id)
    {
        Introducer::where('introduce_id', $id)->delete();
    }

    /**
     * return all phone bill
     *
     * @param [integer] $connectSns
     * @return void
     */
    public function getPhoneBill($connectSns)
    {
        return Introducer::where('introduced_number', $connectSns)->get();
    }

}
