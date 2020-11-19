<?php

namespace App\Http\Controllers;

use App\Contracts\Services\LoginServiceInterface;

class LoginController extends Controller
{
    private $loginService;

    public function __construct(LoginServiceInterface $loginService)
    {
        $this->loginService = $loginService;
    }

    public function test()
    {
        return $this->loginService->test();
    }
}
