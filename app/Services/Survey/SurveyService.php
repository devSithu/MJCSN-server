<?php

namespace App\Services\Survey;

use App\Contracts\Dao\Survey\SurveyDaoInterface;
use App\Contracts\Services\Survey\SurveyServiceInterface;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveyQuestionValidationRule;
use App\Models\ValidationRule;
use App\Models\Visitor;
use App\Models\CommunityUser;
use CpsForm;

class SurveyService implements SurveyServiceInterface
{
    private $surveyDao;

    /**
     * Constructor
     *
     * @param SurveyDaoInterface $SurveyDao
     */
    public function __construct(SurveyDaoInterface $surveyDao)
    {
        $this->surveyDao = $surveyDao;
    }

    /**
     * Survey list function
     *
     * @return void
     */
    public function surveyList()
    {
        return $this->surveyDao->surveyList();
    }

    /**
     * @param [type] $survey_id
     * @return $surveY_count
     */
    public function getSurveyCount($survey_id)
    {
        return $this->surveyDao->getSurveyCount($survey_id);
    }

    /**
     * Get data type function
     *
     * @return void
     */
    public function getDataType()
    {
        return $this->surveyDao->getDataType();
    }

    /**
     * @param [type] $survey_id
     * @return  $surveyQ
     */
    public function getSurveyQuestion($survey_id)
    {
        return $this->surveyDao->getSurveyQuestion($survey_id);
    }

    /**
     * Create survey function
     *
     * @return void
     */
    public function createInformationSurvey()
    {
        if (CpsForm::input('survey_id') != null) {
            $survey = Survey::where('survey_id', CpsForm::input('survey_id'))->first();
        } else {
            $survey = new Survey();
        }

        $survey->name = CpsForm::input("name");
        $survey->url = CpsForm::input("url");
        $survey->start_datetime = CpsForm::input("open_date") . " " . CpsForm::input("open_hour") . ":" . CpsForm::input("open_minute");
        $survey->end_datetime = CpsForm::input("end_date") . " " . CpsForm::input("end_hour") . ":" . CpsForm::input("end_minute");
        $survey->start_screen_message = trim(CpsForm::input("start_screen_message"));
        $survey->finish_screen_message = trim(CpsForm::input("end_screen_message"));

        return $this->surveyDao->createInformationSurvey($survey);
    }

    /**
     * @param [type] $survey_question_id
     * @return mixed
     */
    public function getSurveyQuestionList($survey_question_id, $survey_id)
    {
        return $this->surveyDao->getSurveyQuestionList($survey_question_id, $survey_id);
    }

    /**
     * @param [type] $survey_id
     * @return void
     */
    public function findSurvey($survey_id)
    {
        return $this->surveyDao->findSurvey($survey_id);
    }

    /**
     * Survey question and answer for csv function
     *
     * @param [type] $survey
     * @return void
     */
    public function surveyQuestionAndAnswerForCsv($survey)
    {
        return $this->surveyDao->surveyQuestionAndAnswerForCsv($survey);
    }

    /**
     * Visitor count for csv function
     *
     * @param [type] $survey
     * @return void
     */
    public function surveyVisitorCountForCsv($survey)
    {
        return $this->surveyDao->surveyVisitorCountForCsv($survey);
    }

    /**
     * @param [type] $survey_id
     * @param [type] $column
     * @return void
     */
    public function getSurveyInfo($survey_id, $column)
    {
        return $this->surveyDao->getSurveyInfo($survey_id, $column);
    }

    /**
     * @param [type] $survey_id
     * @return mixed
     */
    public function maxAnswerChoices($survey_id)
    {
        return $this->surveyDao->maxAnswerChoices($survey_id);
    }

    /**
     * @param [type] $survey_id
     * @return mixed
     */
    public function questionChoiceSurvey($survey_id)
    {
        return $this->surveyDao->questionChoiceSurvey($survey_id);
    }

    /**
     * @param [type] $survey
     * @return mixed
     */
    public function forceDeleteSurvey($request)
    {
        $this->surveyDao->forceDeleteSurvey($request);
    }

    /**
     * @return $survey_delete
     */
    public function deleteSurveyList()
    {
        return $this->surveyDao->deleteSurveyList();
    }

    /**
     * @param [type] $survey
     * @return mixed
     */
    public function forceDeleteSurveyQuestion($survey)
    {
        $this->surveyDao->forceDeleteSurveyQuestion($survey);
    }

    /**
     * @param [type] $survey_id
     * @return $survey
     */
    public function getSurvey($survey_id)
    {
        return $this->surveyDao->getSurvey($survey_id);
    }

    /**
     * @param [type] $survey_id
     * @return void
     */
    public function getSurveyQuestionBranch($survey_id)
    {
        return $this->surveyDao->getSurveyQuestionBranch($survey_id);
    }

    /**
     * @param [type] $survey_id
     * @return void
     */
    public function getSurveyVisitor($survey_id)
    {
        return $this->surveyDao->getSurveyVisitor($survey_id);
    }

    /**
     * Search visitor function
     *
     * @param [type] $survey_id
     * @param [type] $request
     * @return void
     */
    public function searchForVisitorInSurvey($survey_id, $request)
    {
        $query = $this->surveyDao->visitorInSurvey($survey_id);

        $filter = $this->buildKeywordFilter($query, $request->input('search.value'), $survey_id);
        $sorter = $this->buildSorter($query, $request, $survey_id);
        $orderDir = $request->input('order.0.dir', 'desc');
        $orderBy = array_get($request->input('columns', []), $request->input('order.0.column', 0) . '.data');
        if ($sorter == null) {
            $result = $query->orderBy("user_number", $orderDir)->get()->filter($filter);
        } else if (gettype($sorter) == 'object') {
            $result = $query->get()->filter($filter)->sortBy($sorter, SORT_REGULAR, $orderDir === "asc")->values();
        } else {
            $result = $query->orderBy($sorter, $orderDir)->orderBy("user_number", $orderDir)->get()->filter($filter);
        }
        return $result;
    }

    /**
     * Get visitor function
     *
     * @param [type] $visitor_id
     * @return void
     */
    public function getVisitor($visitor_id)
    {
        return $this->surveyDao->getVisitor($visitor_id);
    }

    /**
     * Survey visitor function
     *
     * @param [type] $survey_id
     * @return void
     */
    public function surveyVisitor($survey_id, $visitor)
    {
        return $this->surveyDao->surveyVisitor($survey_id, $visitor);
    }

    /**
     * Visitor answer function
     *
     * @param [type] $survey_id
     * @param [type] $survey_visitor
     * @return void
     */
    public function questionVisitorAnswer($survey_id, $survey_visitor)
    {
        return $this->surveyDao->questionVisitorAnswer($survey_id, $survey_visitor);
    }

    /**
     * Delete survey visitor function
     *
     * @param [type] $survey_visitor_id
     * @return void
     */
    public function actionDeleteSurveyVisitor($survey_visitor_id)
    {
        $this->surveyDao->actionDeleteSurveyVisitor($survey_visitor_id);
    }

    /**
     * @param [type] $survey_id
     * @param [type] $items
     * @param [type] $number_page
     * @return void
     */
    public function createSurveyQuestion($survey_id, $items, $number_page)
    {
        $survey_question = new SurveyQuestion();
        $survey_question->survey_id = $survey_id;
        $survey_question->content = $items['label'];
        $survey_question->data_type_id = $items['data_type_id'];
        $survey_question->order = $items['order'] + 1;
        $survey_question->page = $number_page;
        return $this->surveyDao->createSurveyQuestion($survey_question);
    }

    /**
     * @param [type] $items
     * @param [type] $survey_question
     * @return void
     */
    public function createSurveyAnswer($items, $survey_question)
    {
        if (!is_array($items['choices'])) {
            $choices = explode("\n", $items['choices']);
            $choices = array_map('trim', $choices);
            $choices = array_values(array_filter($choices, 'strlen'));
        }
        if (isset($items['allow_other']) && $items['allow_other'] != '') {
            $choices[] = 'その他';
        }

        $data = array_map(function ($value) use ($survey_question) {
            return [
                'survey_question_id' => $survey_question['survey_question_id'],
                'content' => $value,
                'is_other' => ($value == 'その他') ? true : false,
            ];
        }, $choices);
        if (isset($data[$items['is_exclusion']])) {
            $data[$items['is_exclusion']]['is_exclusion'] = true;
        }
        return $this->surveyDao->createSurveyAnswer($survey_question, $data);
    }

    /**
     * Create question branch function
     *
     * @param [type] $branch_condition
     * @param [type] $survey_question
     * @param [type] $question_branch
     * @return void
     */
    public function createQuestionBranch($branch_condition, $survey_question, $question_branch)
    {
        $this->surveyDao->createQuestionBranch($branch_condition, $survey_question, $question_branch);
    }

    /**
     * Validation rule by ID function
     *
     * @param [type] $items
     * @param [type] $survey_question
     * @return void
     */
    public function validationRuleById($items, $survey_question)
    {
        $validation_rule_id = $this->surveyDao->getValidationRule($items['validation_rule_id']);
        $validationRule = new SurveyQuestionValidationRule();
        $validationRule->survey_question_id = $survey_question->survey_question_id;
        $validationRule->validation_rule_id = $validation_rule_id[0]->validation_rule_id;
        $validationRule->parameter = '';
        $this->surveyDao->saveValidationRule($validationRule);
    }

    /**
     * Validation rule by required function
     *
     * @param [type] $survey_question
     * @return void
     */
    public function validationRuleByRequired($survey_question)
    {
        $validationRule = new SurveyQuestionValidationRule();
        $validationRule->survey_question_id = $survey_question->survey_question_id;
        $validationRule->validation_rule_id = ValidationRule::RULE_REQUIRED['validation_rule_id'];
        $validationRule->parameter = '';
        $this->surveyDao->saveValidationRule($validationRule);
    }

    /**
     * Validation rule by max length function
     *
     * @param [type] $items
     * @param [type] $survey_question
     * @return void
     */
    public function validationRuleByMaxlength($items, $survey_question)
    {
        $validationRule = new SurveyQuestionValidationRule();
        $validationRule->survey_question_id = $survey_question->survey_question_id;
        $validationRule->validation_rule_id = ValidationRule::RULE_MAX_LENGTH['validation_rule_id'];
        $validationRule->parameter = $items['max_length'];
        $this->surveyDao->saveValidationRule($validationRule);
    }

    /**
     * Keyword filter function
     *
     * @param [type] $query
     * @param [type] $search
     * @param [type] $survey_id
     * @return void
     */
    protected function buildKeywordFilter($query, $search, $survey_id)
    {
        $keywords = preg_split('/[\s]+/', $this->normalize($search), -1, PREG_SPLIT_NO_EMPTY);
        if (!$keywords) {
            return null;
        }
        return function ($v) use ($keywords) {
            $text = $v->text;
            $text = $this->normalize($text);
            foreach ($keywords as $keyword) {
                if (!str_contains($text, $keyword)) {
                    return false;
                }
            }
            return true;
        };
    }

    /**
     * Normalize function
     *
     * @param [type] $text
     * @return void
     */
    protected function normalize($text)
    {
        return strtolower(mb_convert_kana($text, 'askh', 'UTF-8'));
    }

    /**
     * Sort function
     *
     * @param [type] $query
     * @param [type] $request
     * @param [type] $survey_id
     * @return void
     */
    protected function buildSorter($query, $request, $survey_id)
    {
        $columns = $request->input('columns', []);
        $orderBy = array_get($columns, $request->input('order.0.column', 0) . '.data');

        if (in_array($orderBy, array_merge((new CommunityUser)->getFillable(), ['created_at']))) {
            $query->addSelect($orderBy . ' as sort');
        }
        return 'sort';
    }
}
