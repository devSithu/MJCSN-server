<?php

namespace App\Http\Controllers;

use Auth;
use CpsCSV;
use Session;
use Response;
use Carbon\Carbon;
use App\Models\ActionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Contracts\Services\ActionLogServiceInterface;
use App\Contracts\Services\CommunityServiceInterface;

class CommunityController extends Controller
{
    private $communityService,$actionLogService;

    /**
     * Class Constructor
     * @param communityService
     * @return
     */
    public function __construct(CommunityServiceInterface $communityService,ActionLogServiceInterface $actionLogService)
    {
        $this->communityService = $communityService;
        $this->actionLogService = $actionLogService;
    }

    /**
     * create user for mobile
     *
     * @param Request $request
     * @return void
     */
    public function appCreateUser(Request $request)
    {
       
        $userInfo = $this->appGetUserInfo($request);
        
        $user = $this->communityService->appCreateUser($request);
        // $this->getHeaderData($request);
        return Response::json($user);
    }

    /**
     * Check update from mobile
     *
     *
     * @return void
     */
    public function checkUpdate()
    {
        return Response::json([
            'versioncode' => 6
        ]);
    }

    /**
     * update user for mobile
     *
     * @param Request $request
     * @return void
     */
    public function appUpdateUser(Request $request)
    {
        $userInfo = $this->appGetUserInfo($request);
        try {
            if ($this->communityService->appUpdateUser($userInfo)) {
                // $this->getHeaderData($request);
                return Response::json(['statVal' => 1]);
            } else {
                return Response::json(['statVal' => 0]);
            }
        } catch (\Exception $e) {
            return Response::json(['statVal' => 0]);
        }
    }

    /**
     * user login
     *
     * @param Request $request
     * @return void
     */
    public function appUserLogin(Request $request)
    {
        $userInfo = $this->getLoginID($request); 
        
        try {
            $userData = $this->communityService->appUserLogin($userInfo);
            
            if ($userData) {
                // $this->getHeaderData($request);
                return Response::json($userData);
            } else {
                return Response::json(['statVal' => 0]);
            }
        } catch (\Exception $e) {
            return Response::json(['statVal' => 0]);
        }
    }

    /**
     * WordPress Content Detail from mobile
     *
     * @param Request $request
     * @return void
     */
    public function wpDetail(Request $request)
    {
        // $this->getHeaderData($request);
    }

    /**
     * get header data
     *
     * @param [integer] $id
     * @return void
     */
    public function getHeaderData($request)
    {
        if($request->headers->has('X-Parameter'))
        {
            $this->insertData($request);
        }
        else
        {
            $this->insertDataWithoutXParameter($request);
        }

    }

        /**
     * insert data with x-parameter
     *
     * @param $request
     * @return void
     */
    public function insertData($request)
    {
        $data = [
            'user_number' => $request->header('X-UserID'),
            'action' => $request->header('X-Action'),
            'action_at' => Carbon::now(),
            'parameter' => $request->header('X-Parameter'),
            'point' =>  "",
            'os' => $request->header('X-OS'),
            'os_version' => $request->header('X-OSVersion'),
            'app' => "MJCSN",
            'app_version' => $request->header('X-Version'),
        ];
        
        $this->actionLogService->createHeaderData($data);
    }

        /**
     * insert data withod x-parameter
     *
     * @param $request
     * @return void
     */
    public function insertDataWithoutXParameter($request)
    {
        
        $data = [
            'user_number' => $request->header('X-UserID'),
            'action' => $request->header('X-Action'),
            'action_at' => Carbon::now(),
            'parameter' => "-",
            'point' =>  "",
            'os' => $request->header('X-OS'),
            'os_version' => $request->header('X-OSVersion'),
            'app' => "MJCSN",
            'app_version' => $request->header('X-Version'),
        ];
        
        $this->actionLogService->createHeaderData($data);
    }

    /**
     * get user loginID
     *
     * @param [type] $request
     * @return void
     */
    public function getLoginID($request)
    {
        $userInfo = new \stdClass();
        $userInfo->login_id = $request->login_id;
        return $userInfo;
    }

    /**
     * change user loginID for mobile
     *
     * @param Request $request
     * @return void
     */
    public function appChangeLoginID(Request $request)
    {
        $userInfo = $this->checkUser($request);
        $update_loginID = $request->login_id;
        try {
            $userData = $this->communityService->appChangeLoginID($userInfo, $update_loginID);
            if ($userData) {
                // $this->getHeaderData($request);
                return Response::json(['statVal' => 1]);
            } else {
                return Response::json(['statVal' => 0]);
            }
        } catch (\Exception $e) {
            return Response::json(['statVal' => 0]);
        }
    }

    /**
     * user register
     *
     * @param Request $request
     * @return void
     */
    public function appCheckDuplicateUser(Request $request)
    {
        $userInfo = $this->getRegisterData($request);
        $userData = $this->communityService->appCheckDuplicateUser($userInfo);
        // $this->getHeaderData($request);
        return Response::json($userData);
    }

    /**
     * get user registerData
     *
     * @param [type] $request
     * @return void
     */
    public function getRegisterData($request)
    {
        $userInfo = new \stdClass();
        $userInfo->login_id = $request->login_id;
        // $userInfo->user_name = $request->user_name;
        $userInfo->email = $request->email;
        // $userInfo->nrc_number = $request->nrc_number;
        return $userInfo;
    }

    /**
     * check user for mobile
     *
     *
     * @return void
     */
    public function authCheckUser(Request $request)
    {
        $user = Auth::guard('users')->user();
        
        $profileCols = config('constants.USER_PROFILE_COLUMNS');
        if ($user) {
            // $this->getHeaderData($request);
            return Response::json([
                'success' => true,
                'userData' => $user->only($profileCols),
            ]);
        }
    }

    /**
     * revoke user for mobile
     *
     *
     * @return void
     */
    public function revokeUserAuth(Request $request)
    {
        // $this->getHeaderData($request);
        Auth::guard('users')->user()->token()->delete();
    }

    public $successStatus = 200;

    public function checkToken(Request $request)
    {
        $user = Auth::guard('api')->user();
        // $this->getHeaderData($request);
        return response()->json(['success' => $user], $this->successStatus);
    }

    /**
     * Check user auth function
     *
     * @param [type] $request
     * @return void
     */
    public function checkUser($request)
    {
        $user = Auth::guard('api')->user();
        return $user;
    }

    /**
     * Show community user list
     *
     * @return void
     */
    public function showList()
    {
        Session::forget('USER_SEARCH');
        return view('communityuser.list');
    }

    /**
     * Search function
     *
     * @param Request $request
     * @return void
     */
    public function searchCommunityUsers(Request $request)
    {
        $search = $this->getSearch($request);
        Session::put('USER_SEARCH', $search);
        return redirect()->route('CommunityUser#searchCommunityUsersResult');
    }

    /**
     * Search Result
     *
     * @return void
     */
    public function searchCommunityUsersResult()
    {
        if (Session::has('USER_SEARCH')) {
            $search = Session::get('USER_SEARCH');
            
            $result = $this->communityService->searchCommunityUser($search);
            
            return view('communityuser.list', compact('search', 'result'));
        }
        return redirect()->route('CommunityUser#searchCommunityUsers');
    }

    /**
     * Csv download.
     *
     * @return void
     */
    public function communityUserDownloadCsv()
    {
        $csv_search = Session::get('USER_SEARCH');
        $filename = "community_user" . date('Ymd') . ".csv";
        $csv_text = mb_convert_encoding($this->communityService->getcommunityUserListInCsv($csv_search), 'UTF-8');

        return CpsCSV::download($csv_text, $filename);
    }

    /**
     * Edit community user function
     *
     * @param [type] $userNumber
     * @return void
     */
    public function communityUserEdit($userNumber)
    {
        $communityuser = $this->communityService->getCommunityUser($userNumber);
        if (!$communityuser) {
            Abort(404);
        }
        return view('communityuser.edit', compact('communityuser'));
    }

    /**
     * Update community user status function
     *
     * @return void
     */
    public function updateCommunityUserStatus(Request $request, $userNumber)
    {
        $statusObj = $this->getStatus($request);
        $communityuser = $this->communityService->updateCommunityUserStatus($userNumber, $statusObj);
        return redirect(route('CommunityUser#showList'))->with(["status" => config('constants.MSG_EDIT')]);
    }

    /**
     * Delete community user function
     *
     * @param [type] $userNumber
     * @return void
     */
    public function deleteCommunityUser($userNumber)
    {
        $this->communityService->deleteCommunityUser($userNumber);
        return redirect(route('CommunityUser#showList'))->with(["status" => config('constants.MSG_DELETE')]);
    }

    /**
     * getUserInfo for mobile
     *
     * @param [type] $request
     * @return Object
     */
    private function appGetUserInfo($request)
    {
        $userInfo = new \stdClass();
        $userInfo->user_number = $request->user_number;
        $userInfo->login_id = $request->login_id;
        $userInfo->password = $request->password;
        $userInfo->user_type = $request->user_type;
        $userInfo->user_name = $request->user_name;
        $userInfo->gender = $request->gender;
        $userInfo->date_of_birth = $request->date_of_birth;
        $userInfo->nrc_number = $request->nrc_number;
        $userInfo->graduated_from = $request->graduated_from;
        $userInfo->graduated_dep = $request->graduated_dep;
        $userInfo->graduated_year = $request->graduated_year;
        $userInfo->region = $request->region;
        $userInfo->career = $request->career;
        $userInfo->status = $request->status;
        $userInfo->address = $request->address;
        $userInfo->phone_number = $request->phone_number;
        $userInfo->email = $request->email;
        $userInfo->connect_sns = $request->connect_sns;
        $userInfo->nrc_image = $request->nrc_image;
        $userInfo->answer_one = $request->answer_one;
        $userInfo->answer_two = $request->answer_two;
        $userInfo->answer_three = $request->answer_three;
        $userInfo->answer_four = $request->answer_four;
        return $userInfo;
    }

    /**
     * Make the search keywords as Object
     *
     * @param [type] $request
     * @return object
     */
    private function getSearch($request)
    {
       
        $search = new \stdClass();
        if ($request) {
            $search->user_name = $request->user_name;
            $search->gender = $request->gender;
            $search->fromDate = $request->fromDate;
            $search->toDate = $request->toDate;
            $search->registerFromDate = $request->registerFromDate;
            $search->registerToDate = $request->registerToDate;
            // $search->nrc_number = $request->nrc_number;
            $search->graduated_from = $request->graduated_from;
            $search->graduated_dep = $request->graduated_dep;
            $search->graduated_year = $request->graduated_year;
            // $search->address = $request->address;
            $search->phone_number = $request->phone_number;
            $search->email = $request->email;
            $search->status = $request->status;
        } else {
            $search->user_name = "";
            $search->gender = "";
            $search->fromDate = "";
            $search->toDate = "";
            $search->registerFromDate ="";
            $search->registerToDate = "";
            // $search->nrc_number = "";
            $search->graduated_from = "";
            $search->graduated_dep = "";
            $search->graduated_year = "";
            // $search->address = "";
            $search->phone_number = "";
            $search->email = "";
            $search->status = "";
        }
        
        return $search;
    }

    /**
     * getUserInfo for mobile
     *
     * @param [type] $request
     * @return Object
     */
    private function getStatus($request)
    {
        $userStatus = new \stdClass();
        $userStatus->status = trim($request->status);
        return $userStatus;
    }
}
