<?php

namespace App\Services\Form;

use App\Contracts\Dao\Form\SurveyDaoInterface;
use App\Contracts\Services\Form\SurveyServiceInterface;
use App\Models\Config;

class SurveyService implements SurveyServiceInterface
{
    private $surveyDao;

    /**
     * Constructor
     *
     * @param SurveyDaoInterface $surveyDao
     */
    public function __construct(SurveyDaoInterface $surveyDao)
    {
        $this->surveyDao = $surveyDao;
    }

    /**
     * Get All Survey function
     *
     * @return void
     */
    public function getallSurvey()
    {
        return $this->surveyDao->getallSurvey();
    }

    /**
     * Get visitor function
     *
     * @param [type] $usernumber
     * @return void
     */
    public function getCommunityUserByUserNumber($usernumber)
    {
        return $this->surveyDao->getCommunityUserByUserNumber($usernumber);
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
        return $this->surveyDao->getSurveyBySurveyId($surveyId, $usernumber);
    }

    /**
     * Total page of survey question function
     *
     * @param [type] $surveyId
     * @return void
     */
    public function totalPageofSurveyQuestion($surveyId)
    {
        return $this->surveyDao->totalPageofSurveyQuestion($surveyId);
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
        return $this->surveyDao->getSurveyQuestion($surveyId, $number_page);
    }

    /**
     * User information function
     *
     * @param [type] $usernumber
     * @return void
     */
    public function communityUserInformation($usernumber)
    {
        return $this->surveyDao->communityUserInformation($usernumber);
    }

    /**
     * Create survey visitor function
     *
     * @param [type] $surveyId
     * @param [type] $usernumber
     * @return void
     */
    public function createSurveyVisitor($surveyId, $usernumber)
    {
        $surveyVisitor = ['survey_id' => $surveyId,
            'user_number' => $usernumber];
        return $this->surveyDao->createSurveyVisitor($surveyVisitor);
    }

    /**
     * Survey question function
     *
     * @param [type] $key
     * @return void
     */
    public function findSurveyQuestion($key)
    {
        return $this->surveyDao->findSurveyQuestion($key);
    }

    /**
     * Create answer of visitor function
     *
     * @param [type] $survey_question
     * @param [type] $survey_visitor_id
     * @param [type] $data_answer
     * @param [type] $other_answer
     * @return void
     */
    public function createAnswerOfVisitor($survey_question, $survey_visitor_id, $data_answer, $other_answer)
    {
        if ($data_answer != '') {
            if (($survey_question['data_type_id'] == Config::QUESTION_TEXT || $survey_question['data_type_id'] == Config::QUESTION_TEXTAREA) && trim($data_answer) != '') {
                $data = [
                    'survey_visitor_id' => $survey_visitor_id,
                    'survey_question_id' => $survey_question['survey_question_id'],
                    'content' => trim($data_answer),
                ];
                $this->surveyDao->createAnswerOfVisitor($data);
            }

            if ($survey_question['data_type_id'] == Config::QUESTION_SELECT || $survey_question['data_type_id'] == Config::QUESTION_RADIO) {
                $data = [
                    'survey_visitor_id' => $survey_visitor_id,
                    'survey_question_id' => $survey_question['survey_question_id'],
                    'survey_answer_id' => $data_answer,
                    'content' => $other_answer ? array_key_exists($data_answer, $other_answer) : null ? trim($other_answer[$data_answer]) : null,
                ];
                $this->surveyDao->createAnswerOfVisitor($data);
            }

            if ($survey_question['data_type_id'] == Config::QUESTION_CHECKBOX) {
                foreach ($data_answer as $survey_answer_id) {
                    $data = [
                        'survey_visitor_id' => $survey_visitor_id,
                        'survey_question_id' => $survey_question['survey_question_id'],
                        'survey_answer_id' => $survey_answer_id,
                        'content' => $other_answer ? array_key_exists($survey_answer_id, $other_answer) : null ? trim($other_answer[$survey_answer_id]) : null,
                    ];
                    $this->surveyDao->createAnswerOfVisitor($data);
                }
            }
        }
    }

}
