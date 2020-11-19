<?php

namespace App\Dao\Form;

use App\Contracts\Dao\Form\SurveyDaoInterface;
use App\Models\CommunityUser;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveyVisitor;
use App\Models\SurveyVisitorQuestionAnswer;
use App\Models\Visitor;

class SurveyDao implements SurveyDaoInterface
{
    /**
     * Get All Survey function
     *
     * @return void
     */
    public function getallSurvey()
    {
        $surveys = Survey::select('*')->get();
        return $surveys;
    }

    /**
     * Get visitor function
     *
     * @param [type] $usernumber
     * @return void
     */
    public function getCommunityUserByUserNumber($usernumber)
    {
        $communityuser = CommunityUser::where('user_number', $usernumber)->first();
        return $communityuser;
    }

    /**
     * Get survey function
     *
     * @param [type] $surveyId
     * @param [type] $usernumber
     * @return void
     */
    public function getSurveyBySurveyId($surveyId, $usernumber)
    {
        $visitor_answered_survey = SurveyVisitor::where('survey_id', $surveyId)->where('user_number', $usernumber)->first();
        return $visitor_answered_survey;
    }

    /**
     * Total page of survey question function
     *
     * @param [type] $surveyId
     * @return void
     */
    public function totalPageofSurveyQuestion($surveyId)
    {
        $total_page = SurveyQuestion::select('page')->where('survey_id', $surveyId)->groupBy('page')->get()->count();
        return $total_page;
    }

    /**
     * Get survey question function
     *
     * @param [type] $surveyId
     * @param [type] $number_page
     * @return void
     */
    public function getSurveyQuestion($surveyId, $number_page)
    {
        $list_survey_question = $this->getSurveyQuestionBranch($surveyId)->where('page', $number_page);
        return $list_survey_question;
    }

    /**
     * Get survey question branch function
     *
     * @param [type] $surveyId
     * @return void
     */
    public function getSurveyQuestionBranch($surveyId)
    {
        return SurveyQuestion::where('survey_id', $surveyId)->with(['survey_answers' => function ($query) {
            $query->orderBy('survey_answer_id', 'asc')->get();
        }])->with(['survey_question_branch_conditions'])->with(['survey_question_validation_rules' => function ($query) {
            $query->pluck('validation_rule_id')->toArray();}])->orderBy('order', 'asc')->get();
    }

    /**
     * Community user information function
     *
     * @param [type] $usernumber
     * @return void
     */
    public function communityUserInformation($usernumber)
    {
        $communityuser = CommunityUser::where('user_number', $usernumber)->first();
        return $communityuser;
    }

    /**
     * Create survey visitor function
     *
     * @param [type] $surveyVisitor
     * @return void
     */
    public function createSurveyVisitor($surveyVisitor)
    {
        $survey_visitor_id = SurveyVisitor::create($surveyVisitor);
        return $survey_visitor_id;
    }

    /**
     * Survey question function
     *
     * @param [type] $key
     * @return void
     */
    public function findSurveyQuestion($key)
    {
        $survey_question = SurveyQuestion::find($key);
        return $survey_question;
    }

    /**
     * Create answer of visitor function
     *
     * @param [type] $data
     * @return void
     */
    public function createAnswerOfVisitor($data)
    {
        SurveyVisitorQuestionAnswer::create($data);
    }
}
