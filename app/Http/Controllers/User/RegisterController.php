<?php

namespace App\Http\Controllers\User;

use App\Contracts\Services\UserRegisterServiceInterface;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Response;

class RegisterController extends Controller
{

    private $userRegisterService;

    /**
     * Class Constructor
     * @param userRegisterService
     * @return
     */
    public function __construct(UserRegisterServiceInterface $userRegisterService)
    {
        $this->userRegisterService = $userRegisterService;
    }

    /**
     * go to register page
     *
     * @param [type]
     * @return void
     */
    public function registerPage()
    {
        return view('user.register');
    }

    /**
     * go to login page
     *
     * @param [type]
     * @return void
     */
    public function loginPage()
    {
        return view('user.login');
    }

    /**
     * get data for register
     *
     * @param [type] $request
     * @return void
     */
    public function userRegister(Request $request)
    {
        $rules = array(
            'name' => 'required|max:20',
            'email' => 'required|email|max:100|unique:register_users',
            'password' => 'required|min:8|max:20',
            'password_confirmation' => 'required|same:password|min:8|max:20',
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            $data = $this->userRegisterService->userRegister($request);
            return back()->with('success', config('constants.REGISTER_SUCCESS'));
        }
    }

    //api_register
    private function appGetUserInfo($request)
    {
        $register_info = new \stdClass();
        $register_info->name = $request->name;
        $register_info->email = $request->email;
        $register_info->password = $request->password;

        return $register_info;
    }

    public function appRegister(Request $request)
    {
        $data = $this->appGetUserInfo($request);
        try {
            if ($this->userRegisterService->appRegister($userInfo)) {
                return Response::json(['statVal' => 1]);
            } else {
                return Response::json(['statVal' => 0]);
            }
        } catch (\Exception $e) {
            return Response::json(['statVal' => 0]);
        }
    }

    //close api_register

    /**
     * get data for login
     *
     * @param [type] $request
     * @return void
     */
    public function login(Request $request)
    {
        $rules = array(
            'email' => 'required|email|max:100',
            'password' => 'required|min:8|max:20',
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            $credentials = $request->only('email', 'password');
            if (Auth::guard('admin')->attempt($credentials)) {
                return redirect()->route('BillPayController#billPayList');
            }
            return redirect()->back()->withInput()->with('status', config('constants.MSG_ERROR_LOGIN'));
        }
    }

    /**
     * logout for web and delete session
     *
     * @param [type]
     * @return void
     */
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('RegisterController#login');
    }
}
