<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::group(['middleware' => ['auth:api', 'api.key']], function () {
    Route::post('user/profile', 'CommunityController@checkToken'); 
    Route::post('user/app_changeFBLoginID', 'CommunityController@appChangeLoginID'); 
});

Route::group(['middleware' => ['auth:users', 'api.key']], function () {
    Route::get('authUser/check', 'CommunityController@authCheckUser'); 
    Route::get('authUser/revoke', 'CommunityController@revokeUserAuth'); 

    // get Phone bill
    Route::get('authUser/getPhoneBill', 'BillPayController@getPhoneBill'); 
});

Route::group(['middleware' => 'api.key'], function () {
    Route::post('introduce/deleteBill', 'BillPayController@deleteBill') ; 

    Route::post('user/app_userLogin', 'CommunityController@appUserLogin');  
    Route::post('user/app_createUser', 'CommunityController@appCreateUser'); 
    Route::post('user/app_checkDuplicateUser', 'CommunityController@appCheckDuplicateUser');  
    Route::post('user/app_updateUser', 'CommunityController@appUpdateUser'); 
    Route::get('wpUser/contentDetail', 'CommunityController@wpDetail'); 
    Route::get('appUser/checkUpdate', 'CommunityController@checkUpdate');
    
    Route::post('firebase_token_store', 'FirebaseTokenController@tokenStore'); 

    Route::group(['prefix' => 'survey'], function () {
        Route::get('/list', 'Form\SurveyController@getAllSurvey'); 
    });
});
