<?php

namespace App\Contracts\Services;

interface AdminServiceInterface
{
    public function getRegisterUser();
    public function deleteAdminAccount($id);
    public function updateAdminAccount($id);
    public function updateAccount($id, $data);
}
