<?php

namespace App\Http\Controllers;

use App\Contracts\Services\AdminServiceInterface;
use Illuminate\Http\Request;
use Validator;

class AdminController extends Controller
{

    private $adminService;

    /**
     * Class Constructor
     * @param adminService
     * @return
     */
    public function __construct(AdminServiceInterface $adminService)
    {
        $this->adminService = $adminService;
    }

    /**
     * update admin data
     *
     * @param -
     * @return void
     */
    public function adminList()
    {
        $adminData = $this->adminService->getRegisterUser();
        return view('admin.adminlist')->with(['data' => $adminData]);
    }

    /**
     * delete admin account
     *
     * @param [integer] $id
     * @return void
     */
    public function deleteAdminAccount($id)
    {
        $this->adminService->deleteAdminAccount($id);
        return back()->with(["status" => config('constants.MSG_DELETE')]);
    }

    /**
     * update admin account page
     *
     * @param [integer] $id
     * @return void
     */
    public function updatePage($id)
    {
        $adminData = $this->adminService->updateAdminAccount($id);

        return view('admin.adminlistupdate')->with(['data' => $adminData]);
    }

    /**
     * update admin account
     *
     * @param [integer] $request,$id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:register_users,email,'.$id.',user_id'
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }
        $data = $this->getUpdateUserInfo($request);
        $this->adminService->updateAccount($id, $data);
        return redirect(route('AdminController#adminAccountList'))->with('success', config('constants.MSG_EDIT'));
    }

    /**
     * User info function
     *
     * @param [type] $request
     * @return void
     */
    private function getUpdateUserInfo($request)
    {
        $registerInfo = new \stdClass();
        $registerInfo->name = $request->name;
        $registerInfo->email = $request->email;

        return $registerInfo;
    }
}
