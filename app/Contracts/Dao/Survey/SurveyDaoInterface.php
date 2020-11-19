<?php

namespace App\Contracts\Dao\Survey;

interface SurveyDaoInterface
{
    public function surveyList();
    public function getSurveyCount($survey_id);
    public function getDataType();
    public function getSurveyQuestion($survey_id);
    public function createInformationSurvey($survey);
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
    public function visitorInSurvey($survey_id);
    public function getVisitor($visitor_id);
    public function surveyVisitor($survey_id, $visitor);
    public function questionVisitorAnswer($survey_id, $survey_visitor);
    public function actionDeleteSurveyVisitor($survey_visitor_id);
    public function createSurveyQuestion($survey_question);
    public function createSurveyAnswer($survey_question, $data);
    public function createQuestionBranch($branch_condition, $survey_question, $question_branch);
    public function getValidationRule($validation_rule_id);
    public function saveValidationRule($validationRule);
}
