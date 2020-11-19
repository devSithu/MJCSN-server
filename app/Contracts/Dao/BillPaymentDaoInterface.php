<?php

namespace App\Contracts\Dao;

interface BillPaymentDaoInterface
{
    public function billPayment();
    public function getUserData($loginId);
    public function updatePhoneBill($number, $phoneBill);
    public function getIntroducedUsers($loginId);
    public function deleteBill($id);
    public function getPhoneBill($connectSns);
}
