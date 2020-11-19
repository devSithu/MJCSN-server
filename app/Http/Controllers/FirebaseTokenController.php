<?php

namespace App\Http\Controllers;

use Response;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\FirebaseToken;
use App\Contracts\Services\ActionLogServiceInterface;
use App\Contracts\Services\FirebaseTokenServiceInterface;

class FirebaseTokenController extends Controller
{
    private $firebaseTokenService,$actionLogService;

    /**
     * Class Constructor
     * @param firebaseTokenService
     * @return
     */
    public function __construct(FirebaseTokenServiceInterface $firebaseTokenService,ActionLogServiceInterface $actionLogService)
    {
        $this->firebaseTokenService = $firebaseTokenService;
        $this->actionLogService = $actionLogService;
    }

    /**
     * Firebase Token store function
     *
     * @param Request $request
     * @return void
     */
    public function tokenStore(Request $request)
    {
        $data = $this->getTokenStore($request);
        list($message) = $this->firebaseTokenService->tokenStore($data);
        // $this->getHeaderData($request);
        return Response::json(['message' => $message]);
    }

    /**
     * Get request as object
     *
     * @param [type] $request
     * @return void
     */
    private function getTokenStore($request)
    {
        $tokenStore = new \stdClass();
        $tokenStore->login_id = $request->login_id;
        $tokenStore->fcm_token = $request->fcm_token;
        return $tokenStore;
    }

    //get header data
    public function getHeaderData($request)
    {
        $data = [
            'user_number' => $request->header('X-UserID'),
            'action' => $request->header('X-Action'),
            'action_at' => Carbon::now(),
            'parameter' => '-',
            'point' =>  "",
            'os' => $request->header('X-OS'),
            'os_version' => $request->header('X-OSVersion'),
            'app' => "MJCSN",
            'app_version' => $request->header('X-Version'),
        ];
        $this->actionLogService->createHeaderData($data);

    }

}
