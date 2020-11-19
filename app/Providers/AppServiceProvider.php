<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Community any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Contracts\Services\CommunityServiceInterface', 'App\Services\CommunityService'); //community
        $this->app->bind('App\Contracts\Services\BillPaymentServiceInterface', 'App\Services\BillPaymentService'); //billPayment
        $this->app->bind('App\Contracts\Services\UserRegisterServiceInterface', 'App\Services\UserRegisterService'); //userRegister
        $this->app->bind('App\Contracts\Services\AdminServiceInterface', 'App\Services\AdminService'); //admin
        $this->app->bind('App\Contracts\Services\ActionLogServiceInterface', 'App\Services\ActionLogService'); //actionLog

        $this->app->bind('App\Contracts\Dao\CommunityDaoInterface', 'App\Dao\CommunityDao'); //community
        $this->app->bind('App\Contracts\Dao\BillPaymentDaoInterface', 'App\Dao\BillPaymentDao'); //billPayment
        $this->app->bind('App\Contracts\Dao\UserRegisterDaoInterface', 'App\Dao\UserRegisterDao'); //userRegister
        $this->app->bind('App\Contracts\Dao\AdminDaoInterface', 'App\Dao\AdminDao'); //admin
        $this->app->bind('App\Contracts\Dao\ActionLogDaoInterface', 'App\Dao\ActionLogDao'); //actionLog

        // Survey
        $this->app->bind('App\Contracts\Services\Survey\SurveyServiceInterface', 'App\Services\Survey\SurveyService');
        $this->app->bind('App\Contracts\Dao\Survey\SurveyDaoInterface', 'App\Dao\Survey\SurveyDao');

        // Form
        $this->app->bind('App\Contracts\Services\Form\SurveyServiceInterface', 'App\Services\Form\SurveyService');
        $this->app->bind('App\Contracts\Dao\Form\SurveyDaoInterface', 'App\Dao\Form\SurveyDao');

        $this->app->bind('App\Contracts\Services\FirebaseTokenServiceInterface', 'App\Services\FirebaseTokenService'); //firebase
        $this->app->bind('App\Contracts\Dao\FirebaseTokenDaoInterface', 'App\Dao\FirebaseTokenDao'); // firebase
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \App\Lib\CpsBlade\Extension::extendBlade();
    }
}
