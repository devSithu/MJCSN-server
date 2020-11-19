<?php

namespace App\Contracts\Dao\Form;

interface SurveyDaoInterface
{
    public function getallSurvey();
    public function getCommunityUserByUserNumber($usernumber);
    public function getSurveyBySurveyId($surveyId, $usernumber);
    public function totalPageofSurveyQuestion($surveyId);
    public function getSurveyQuestion($surveyId, $number_page);
    public function getSurveyQuestionBranch($surveyId);
    public function communityUserInformation($usernumber);
    public function createSurveyVisitor($surveyVisitor);
    public function findSurveyQuestion($key);
    public function createAnswerOfVisitor($data);
}
