<?php

namespace App\Services;

use App\Contracts\Dao\BillPaymentDaoInterface;
use App\Contracts\Services\BillPaymentServiceInterface;
use Illuminate\Support\Str;

class BillPaymentService implements BillPaymentServiceInterface
{
    private $billPaymentDao;

    /**
     * Class Constructor
     * @param billPaymentDaoInterface
     * @return
     */
    public function __construct(BillPaymentDaoInterface $billPaymentDao)
    {
        $this->billPaymentDao = $billPaymentDao;
    }

    /**
     * pay bill for connect person
     *
     * @param -
     * @return void
     */
    public function billPayment()
    {
        return $this->billPaymentDao->billPayment();
    }

    /**
     * get user data with login id
     *
     * @param [integer] $login_id
     * @return void
     */
    public function getUserData($loginId)
    {
        return $this->billPaymentDao->getUserData($loginId);
    }

    /**
     * get introducer user phone bill
     *
     * @param [integer] $loginId
     * @return void
     */
    public function getIntroducedUsers($loginId)
    {
        $data = $this->billPaymentDao->getIntroducedUsers($loginId);
        $billData = array();

        foreach ($data as $key => $value) {
            $billNumber = $value->charge_code;
            $one = Str::substr($billNumber, 0, 2);
            $two = Str::substr($billNumber, 2, 4);
            $three = Str::substr($billNumber, 6, 4);
            $four = Str::substr($billNumber, 10, 4);
            $five = Str::substr($billNumber, 14, 4);

            $bill[$key] = [
                'data' => $value,
                'one' => $one,
                'two' => $two,
                'three' => $three,
                'four' => $four,
                'five' => $five,
            ];
        }
        return $bill;
    }

    /**
     * update phone bill
     *
     * @param [integer] $phoneBill
     * @return void
     */
    public function updatePhoneBill($request)
    {
        foreach ($request as $key => $value) {
            if ($key == $value) {
                $bill = $request["${key}_bill_one"] . $request["${key}_bill_two"] . $request["${key}_bill_three"] . $request["${key}_bill_four"] . $request["${key}_bill_five"];
                $this->billPaymentDao->updatePhoneBill($key, $bill);
            }
        }
    }

    /**
     * delete phone bill
     *
     * @param [integer] $id
     * @return void
     */
    public function deleteBill($id)
    {
        $this->billPaymentDao->deleteBill($id);
    }

    /**
     * get phone bill
     *
     * @param [integer] $connectSns
     * @return void
     */
    public function getPhoneBill($connectSns)
    {
        return $this->billPaymentDao->getPhoneBill($connectSns);
    }
}
