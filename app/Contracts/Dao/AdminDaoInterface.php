<?php

namespace App\Contracts\Dao;

interface AdminDaoInterface
{
    public function getRegisterUser();
    public function deleteAdminAccount($id);
    public function updateAdminAccount($id);
    public function updateAccount($id, $updateData);
}
