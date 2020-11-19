<?php

namespace App\Contracts\Services;

interface ActionLogServiceInterface
{
    public function createHeaderData($data);
    public function changeID($data);

}
