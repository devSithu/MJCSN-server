<?php

namespace App\Dao;

use App\Contracts\Dao\AdminDaoInterface;
use App\Models\RegisterUser;

class AdminDao implements AdminDaoInterface
{
    /**
     * register admin account
     *
     * @param []
     * @return void
     */
    public function getRegisterUser()
    {
        return RegisterUser::get();
    }

    /**
     * delete admin account
     *
     * @param [integer] $id
     * @return void
     */
    public function deleteAdminAccount($id)
    {
        RegisterUser::findOrFail($id)->delete();
    }

    /**
     * update admin page
     *
     * @param [integer] $id
     * @return void
     */
    public function updateAdminAccount($id)
    {
        return RegisterUser::findOrFail($id);
    }

    /**
     * update admin account
     *
     * @param [integer] $id , $updateData
     * @return void
     */
    public function updateAccount($id, $updateData)
    {
        RegisterUser::findOrFail($id)->update($updateData);
    }
}
