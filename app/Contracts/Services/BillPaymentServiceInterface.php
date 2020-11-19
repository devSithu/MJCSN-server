<?php

namespace App\Contracts\Services;

interface BillPaymentServiceInterface
{
    public function billPayment();
    public function getUserData($loginId);
    public function updatePhoneBill($request);
    public function getIntroducedUsers($loginId);
    public function deleteBill($id);
    public function getPhoneBill($connectSns);
}
