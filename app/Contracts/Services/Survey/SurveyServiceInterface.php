<?php

namespace App\Contracts\Services\Survey;

interface SurveyServiceInterface
{
    public function surveyList();
    public function getSurveyCount($survey_id);
    public function getDataType();
    public function getSurveyQuestion($survey_id);
    public function createInformationSurvey();
    public function getSurveyQuestionList($survey_question_id, $survey_id);
    public function findSurvey($survey_id);
    public function surveyQuestionAndAnswerForCsv($survey);
    public function surveyVisitorCountForCsv($survey);
    public function getSurveyInfo($survey_id, $column);
    public function maxAnswerChoices($survey_id);
    public function questionChoiceSurvey($survey_id);
    public function forceDeleteSurvey($surveyId);
    public function deleteSurveyList();
    public function forceDeleteSurveyQuestion($survey);
    public function getSurvey($survey_id);
    public function getSurveyQuestionBranch($survey_id);
    public function getSurveyVisitor($survey_id);
    public function searchForVisitorInSurvey($survey_id, $request);
    public function getVisitor($visitor_id);
    public function surveyVisitor($survey_id, $visitor);
    public function questionVisitorAnswer($survey_id, $survey_visitor);
    public function actionDeleteSurveyVisitor($survey_visitor_id);
    public function createSurveyQuestion($survey_id, $items, $number_page);
    public function createSurveyAnswer($items, $survey_question);
    public function createQuestionBranch($branch_condition, $survey_question, $question_branch);
    public function validationRuleById($items, $survey_question);
    public function validationRuleByRequired($survey_question);
    public function validationRuleByMaxlength($items, $survey_question);
}
