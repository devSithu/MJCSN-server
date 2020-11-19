<?php

Route::get('/', 'User\RegisterController@loginPage')->name('RegisterController#home');
Route::get('/userlogin', 'User\RegisterController@loginPage')->name('RegisterController#userLogin');
Route::post('/userlogin', 'User\RegisterController@login')->name('RegisterController#login');

Route::group(['middleware' => ['auth:admin']], function () {
    Route::get('/userregister', 'User\RegisterController@registerPage')->name('RegisterController#registerPage');
    Route::post('/userregister', 'User\RegisterController@userRegister')->name('RegisterController#register');
    Route::get('/userlogout', 'User\RegisterController@logout')->name('RegisterController#logout');

    // admin
    Route::group(['prefix' => 'admin'], function () {
        Route::get('/', 'AdminController@adminList')->name('AdminController#adminAccountList');
        Route::delete('delete/{id}', 'AdminController@deleteAdminAccount')->name('AdminController#deleteAdminAccount');
        Route::get('updatepage/{id}', 'AdminController@updatePage')->name('AdminController@updateAdminAccount');
        Route::post('update/{id}', 'AdminController@update')->name('AdminController#update');
    });

    // bill payment
    Route::group(['prefix' => 'bill'], function () {
        Route::get('/', 'BillPayController@billPayList')->name('BillPayController#billPayList');
        Route::get('/payperson/{loginId}', 'BillPayController@payPerson')->name('BillPayController#payperson');
        Route::post('/paybill', 'BillPayController@payPersonBill')->name('BillPayController#payPersonBill');
    });

    // community user
    Route::group(['prefix' => 'communityuser'], function () {
        Route::get('/search', 'CommunityController@showList')->name('CommunityUser#showList');
        Route::post('/list', 'CommunityController@searchCommunityUsers')->name('CommunityUser#searchCommunityUsers');
        Route::get('/search/result', 'CommunityController@searchCommunityUsersResult')
            ->name('CommunityUser#searchCommunityUsersResult');
        Route::get('/csvdownload', 'CommunityController@communityUserDownloadCsv')
            ->name('CommunityUser#communityUserDownloadCsv');

        Route::get('search/edit/{user_number}', 'CommunityController@communityUserEdit')
            ->name('CommunityUser#communityUserEdit');
        Route::post('search/edit/{user_number}', 'CommunityController@updateCommunityUserStatus')
            ->name('CommunityUser#updateCommunityUserStatus');
        Route::delete('delete/{user_number}', 'CommunityController@deleteCommunityUser')
            ->name('CommunityUser#deleteCommunityUser');    
    });

    /**
     * アンケート
     */
    Route::group(['prefix' => 'survey'], function () {
        Route::get("/", 'Survey\SurveyController@showList')
            ->name('user_show_survey_list');

        Route::get("{survey_id}", 'Survey\SurveyController@showSurveyDetail')
            ->name('url_user_show_survey_detail', 'アンケート詳細');

        Route::get("create/step1", 'Survey\SurveyController@showCreateSurveyFormStep1')
            ->name('user_show_create_survey_form1', 'アンケート新規作成');

        Route::post("step1", 'Survey\SurveyController@validateCreateSurveyFormStep1')
            ->name('validate_create_survey_form1');

        Route::get("create/step2", 'Survey\SurveyController@showCreateSurveyFormStep2')
            ->name('user_show_create_survey_form2', 'アンケート新規作成');

        Route::post("create", 'Survey\SurveyController@actionCreateSurvey')
            ->name('user_create_survey');

        Route::get("{survey_id}/download_csv_survey_answer_text", 'Survey\SurveyController@actionDownloadSurveyAnswerText')
            ->name('user_download_csv_survey_answer_question_text');

        Route::get("{survey_id}/user_download_csv_survey", 'Survey\SurveyController@actionSurveyCsvDownload')
            ->name('user_download_csv_survey');

        Route::get("{survey_id}/user_download_csv_survey_question", 'Survey\SurveyController@actionSurveyQuestionCsvDownload')
            ->name('user_download_csv_survey_question');

        Route::delete("", 'Survey\SurveyController@actionDeleteSurvey')
            ->name('user_delete_survey');

        Route::get("{survey_id}/edit", 'Survey\SurveyController@userSettingEditingSurvey')
            ->name('user_setting_editing_survey', 'アンケート編集');

        Route::post("{survey_id}/post_edit/step1", 'Survey\SurveyController@actionEditSurveyFormStep1')
            ->name('validate_edit_survey_form1');

        Route::get("{survey_id}/edit/step2", 'Survey\SurveyController@showEditSurveyFormStep2')
            ->name('user_show_edit_survey_form2', 'アンケート編集');

        Route::post("{survey_id}/edit", 'Survey\SurveyController@actionEditSurvey')
            ->name('user_edit_survey');

        Route::post("preview", 'Survey\SurveyController@showPreviewSurvey')
            ->name('user_show_preview_survey', 'プレビュー');

        Route::get("preview/first-page/{page}", 'Survey\SurveyController@showPreview')
            ->name('user_show_preview', 'プレビュー');

        Route::post("preview/next/{page}", 'Survey\SurveyController@showNextPreviewSurvey')
            ->name('preview_next_page_survey_question', 'プレビュー');

        Route::get("preview/page/{page}", 'Survey\SurveyController@showNextPreview')
            ->name('preview_next_page', 'プレビュー');

        Route::any("preview/finish", 'Survey\SurveyController@showFinishPreviewSurvey')
            ->name('preview_finish_page_survey_question', 'プレビュー');
    });

    /**
     * アンケート管理
     */
    Route::group(['prefix' => 'detail_survey'], function () {
        // show survey list
        Route::get("/", 'Survey\SurveyController@showSurveyList')
            ->name('show_detail_survey_list', 'アンケート集計');

        // show survey detail
        Route::get("{survey_id}", 'Survey\SurveyController@showSurveyDetailData')
            ->name('url_user_show_survey_detail_data', 'アンケート詳細');

        // show visitor list survey answered
        Route::get("{survey_id}/detail_survey", 'Survey\SurveyController@showSurveyVisitor')
            ->name('QB::Survey#show_visitor_list', 'アンケート詳細');

        // delete survey_visitor_list
        Route::delete("{survey_id}/delete/{survey_visitor_id}", 'Survey\SurveyController@actionDeleteSurveyVisitor')
            ->name('user_delete_survey_visitor');

        // survey_visitor_list
        Route::post("{survey_id}/get_survey_visitor", 'Survey\SurveyController@getSurveyVisitorList')
            ->name('QB::Survey#get_visitor');

        // show visitor detail list
        Route::get("{survey_id}/detail-answer-visitor/{visitor_id}", 'Survey\SurveyController@showDetailAnswerVisitor')
            ->name('user_show_detail_answer_visitor', '回答者詳細');

        // show survey question and answer
        Route::get("{survey_id}/survey_question_and_answer", 'Survey\SurveyController@showSurveyQandA')
            ->name('QB::Survey#show_survey_question_and_answer', 'アンケート詳細');
    });
});

// survey answer
Route::group(['prefix' => 'e/'], function () {
    Route::get("/", 'Form\SurveyController@allSurvey')->name('show_all_survey');
    Route::group(['prefix' => '{url}/', 'middleware' => ['surveyMiddleware:survey']], function () {
        Route::get("/", 'Form\SurveyController@loginSurvey')->name('qvisitor_show_survey');
        Route::post("/", 'Form\SurveyController@postLoginSurvey')->name('qvisitor_login_question_survey');

        Route::get("/question", 'Form\SurveyController@showQuestionSurvey')->name('qvisitor_create_survey_question');
        Route::post("/validate", 'Form\SurveyController@validateQuestion')->name('validate_question');
        Route::get("/next-question", 'Form\SurveyController@showQuestionSurveyNextPage')->name('qvisitor_next_page_survey_question');
        Route::get("/finish-survey", 'Form\SurveyController@showFinishSurvey')->name('qvisitor_finish_survey');
    });
});
