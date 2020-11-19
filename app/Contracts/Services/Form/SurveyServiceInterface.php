<?php

namespace App\Contracts\Services\Form;

interface SurveyServiceInterface
{
    public function getallSurvey();
    public function getCommunityUserByUserNumber($usernumber);
    public function getSurveyBySurveyId($surveyId, $usernumber);
    public function totalPageofSurveyQuestion($surveyId);
    public function getSurveyQuestion($surveyId, $number_page);
    public function communityUserInformation($usernumber);
    public function createSurveyVisitor($surveyId, $usernumber);
    public function findSurveyQuestion($key);
    public function createAnswerOfVisitor($survey_question, $survey_visitor_id, $data_answer, $other_answer);
}
