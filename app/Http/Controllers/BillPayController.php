<?php

namespace App\Http\Controllers;

use Response;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Contracts\Services\ActionLogServiceInterface;
use App\Contracts\Services\BillPaymentServiceInterface;

class BillPayController extends Controller
{

    private $billPaymentService,$actionLogService;

    /**
     * Class Constructor
     * @param billPaymentService
     * @return
     */
    public function __construct(BillPaymentServiceInterface $billPaymentService,ActionLogServiceInterface $actionLogService)
    {
        $this->billPaymentService = $billPaymentService;
        $this->actionLogService = $actionLogService;
    }

    /**
     * show bill payment list
     *
     * @param -
     * @return void
     */
    public function billPayList()
    {
        $paymentData = $this->billPaymentService->billPayment();
        return view('bill.billpaylist')->with(["data" => $paymentData]);
    }

    /**
     * pay person
     *
     * @param [integer] $user_number
     * @return void
     */
    public function payPerson($loginId)
    {
        $userData = $this->billPaymentService->getUserData($loginId);
        $introducedUsers = $this->billPaymentService->getIntroducedUsers($loginId);
        return view('bill.payperson')->with(['userData' => $userData, 'introducedUsers' => $introducedUsers]);
    }

    /**
     * pay person bill
     *
     * @param [integer] $request,$login_id,$connect_sns
     * @return void
     */
    public function payPersonBill(Request $request)
    {
        $this->billPaymentService->updatePhoneBill($request->all());
        return back()->with('success', config('constants.MSG_PAYMENT'));
    }

    /**
     * get phone bill
     *
     * @param [integer] $request
     * @return void
     */
    public function getPhoneBill(Request $request)
    {
        $result = Auth::guard('users')->user();
        $connectSns = $result->login_id;
        $result = $this->billPaymentService->getPhoneBill($connectSns);
        // $this->getHeaderData($request);
        return Response::json($result);
    }

    /**
     * delete introduce phone bill
     *
     * @param [integer] $request
     * @return void
     */
    public function deleteBill(Request $request)
    {
        $introduce_id = $request->id;
        $this->billPaymentService->deleteBill($introduce_id);
        // $this->getHeaderData($request);
        return config('constants.MSG_DELETE');
    }

      /**
     * get header data
     *
     * @param [integer] $request
     * @return void
     */
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
