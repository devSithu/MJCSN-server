<?php

namespace App\Services;

use App\Contracts\Dao\AdminDaoInterface;
use App\Contracts\Services\AdminServiceInterface;
use Illuminate\Support\Str;

class AdminService implements AdminServiceInterface
{
    private $adminDao;

    /**
     * Class Constructor
     * @param adminDaoInterface
     * @return
     */
    public function __construct(AdminDaoInterface $adminDao)
    {
        $this->adminDao = $adminDao;
    }

    /**
     * register admin account
     *
     * @param [-]
     * @return void
     */
    public function getRegisterUser()
    {
        return $this->adminDao->getRegisterUser();
    }

    /**
     * delete admin account
     *
     * @param [integer] $id
     * @return void
     */
    public function deleteAdminAccount($id)
    {
        $this->adminDao->deleteAdminAccount($id);
    }

    /**
     * go admin update page
     *
     * @param [integer] $id
     * @return void
     */
    public function updateAdminAccount($id)
    {
        return $this->adminDao->updateAdminAccount($id);
    }

    /**
     * update admin account
     *
     * @param [integer] $id , $data
     * @return void
     */
    public function updateAccount($id, $data)
    {
        $updateData = [
            'name' => $data->name,
            'email' => $data->email,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $this->adminDao->updateAccount($id, $updateData);
    }
}
