<?php

namespace App\Dao\Survey;

use App\Contracts\Dao\Survey\SurveyDaoInterface;
use App\Models\CommunityUser;
use App\Models\Datatype;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveyVisitor;
use App\Models\Visitor;
use DB;

class SurveyDao implements SurveyDaoInterface
{

    /**
     * Survey list function
     *
     * @return $survey_list
     */
    public function surveyList()
    {
        $list_survey = Survey::withCount('survey_visitors')->orderBy('created_at', 'desc')->get();
        return $list_survey;
    }

    /**
     * @param [type] $survey_id
     * @return $survey_count
     */
    public function getSurveyCount($survey_id)
    {
        $survey = Survey::where('survey_id', $survey_id)->withCount('survey_visitors')->first();
        return $survey;
    }

    /**
     * Get data type function
     *
     * @return void
     */
    public function getDataType()
    {
        return DataType::all();
    }

    /**
     * Undocumented function
     *
     * @param [type] $survey_id
     * @return  $surveyQ
     */
    public function getSurveyQuestion($survey_id)
    {
        $list_survey_question = SurveyQuestion::where('survey_id', $survey_id)
            ->with(['survey_visitor_question_answers', 'data_type', 'validation_rules', 'survey_question_branch_conditions'])
            ->with(['survey_answers' => function ($query) {
                $query->orderBy('survey_answer_id', 'asc')->withCount('survey_visitor_question_answers')->get();
            }])
            ->withCount('survey_visitor_question_answers')->orderBy('order', 'asc')->get();
        return $list_survey_question;
    }

    /**
     * Create survey function
     *
     * @return void
     */
    public function createInformationSurvey($survey)
    {
        $survey->save();
        return $survey;
    }

    /**
     * @param [type] $request
     * @return void
     */
    public function getSurveyQuestionList($survey_question_id, $survey_id)
    {
        $list_survey_question = $this->getSurveyQuestion($survey_id)->where('survey_question_id', $survey_question_id)->first();
        return $list_survey_question;
    }

    /**
     * @param [type] $survey_id
     * @return void
     */
    public function findSurvey($survey_id)
    {
        $survey = Survey::find($survey_id);
        return $survey;
    }

    /**
     * Survey question and answer for csv function
     *
     * @param [type] $survey
     * @return void
     */
    public function surveyQuestionAndAnswerForCsv($survey)
    {
        $survey_questions = $survey->survey_questions()
            ->with(['survey_answers' => function ($query) {
                $query->orderBy('survey_answer_id');
            }])->orderBy('order')->get();
        return $survey_questions;
    }

    /**
     * Visitor count for csv function
     *
     * @param [type] $survey
     * @return void
     */
    public function surveyVisitorCountForCsv($survey)
    {
        $total_answered_visitor = $survey->survey_visitors()->get()->count();
        return $total_answered_visitor;
    }

    /**
     * @param [type] $survey_id
     * @param [type] $column
     * @return void
     */
    public function getSurveyInfo($survey_id, $column)
    {
        $query = Survey::where('surveys.survey_id', $survey_id)
            ->leftJoin('survey_visitors', 'surveys.survey_id', 'survey_visitors.survey_id')
            ->leftJoin('community_user', 'survey_visitors.user_number', 'community_user.user_number')
            ->leftJoin('survey_visitor_question_answers', 'survey_visitors.survey_visitor_id', 'survey_visitor_question_answers.survey_visitor_id')
            ->leftJoin('survey_answers', 'survey_visitor_question_answers.survey_answer_id', 'survey_answers.survey_answer_id')
            ->orderBy('visitorId')
            ->select($column);
        return $query->get()->groupBy('visitorId')->toArray();
    }

    /**
     * @param [type] $survey_id
     * @return mixed
     */
    public function maxAnswerChoices($survey_id)
    {
        return SurveyQuestion::leftJoin('survey_answers', 'survey_questions.survey_question_id', 'survey_answers.survey_question_id')
            ->where('survey_id', $survey_id)
            ->where('survey_answers.is_other', false)
            ->selectRaw("survey_answers.survey_question_id, count(*)")
            ->groupBy('survey_answers.survey_question_id')
            ->get()
            ->max('count');
    }

    /**
     * @param [type] $survey_id
     * @return mixed
     */
    public function questionChoiceSurvey($survey_id)
    {
        return SurveyQuestion::leftJoin('survey_answers', 'survey_questions.survey_question_id', 'survey_answers.survey_question_id')
            ->where('survey_id', $survey_id)
            ->where(function ($query) {
                $query->where('survey_answers.is_other', false)
                    ->orWhere('survey_answers.survey_answer_id', null);
            })
            ->selectRaw('survey_questions.survey_question_id, survey_questions.content as question_text, survey_answers.content as answer_choice')
            ->orderBy('survey_questions.survey_question_id')
            ->orderBy('survey_answer_id')
            ->get();
    }

    /**
     * @param [type] $survey
     * @return mixed
     */
    public function forceDeleteSurvey($surveyId)
    {
        Survey::find($surveyId)->forceDelete();
    }

    /**
     * @return $survey_delete
     */
    public function deleteSurveyList()
    {
        $list_survey = Survey::orderBy('created_at', 'asc')->pluck('survey_id')->toArray();
        return $list_survey;
    }

    /**
     * @param [type] $survey
     * @return mixed
     */
    public function forceDeleteSurveyQuestion($survey)
    {
        SurveyQuestion::where('survey_id', $survey->survey_id)->forceDelete();
    }

    /**
     * Get survey function
     *
     * @param [type] $survey_id
     * @return void
     */
    public function getSurvey($survey_id)
    {
        $survey = Survey::where('survey_id', $survey_id)->first();
        return $survey;
    }

    /**
     * @param [type] $survey_id
     * @return void
     */
    public function getSurveyQuestionBranch($survey_id)
    {
        $survey_questions = SurveyQuestion::where('survey_id', $survey_id)->with(['survey_answers' => function ($query) {
            $query->orderBy('survey_answer_id', 'asc')->get();
        }])->with(['survey_question_branch_conditions'])->with(['survey_question_validation_rules' => function ($query) {$query->pluck('validation_rule_id')->toArray();}])->orderBy('order', 'asc')->get();
        return $survey_questions;
    }

    /**
     * @param [type] $survey_id
     * @return void
     */
    public function getSurveyVisitor($survey_id)
    {
        $surveyVisitor = SurveyVisitor::where('survey_id', $survey_id)->get();
        return $surveyVisitor;
    }

    /**
     * Search visitor function
     *
     * @param [type] $survey_id
     * @return void
     */
    public function visitorInSurvey($survey_id)
    {
        $query = CommunityUser::whereHas('survey_visitors', function ($query) use ($survey_id) {
            $query->where('survey_id', '=', $survey_id);})
            ->select('community_user.*', DB::raw("concat_ws(' ', email, user_name) as text"));
        return $query;
    }

    /**
     * Get visitor function
     *
     * @param [type] $visitor_id
     * @return void
     */
    public function getVisitor($visitor_id)
    {
        $visitor = CommunityUser::where('user_number', $visitor_id)->first();
        return $visitor;
    }

    /**
     * Survey visitor function
     *
     * @param [type] $survey_id
     * @return void
     */
    public function surveyVisitor($survey_id, $visitor)
    {
        $survey_visitor = $visitor->survey_visitors->where('survey_id', $survey_id)->first();
        return $survey_visitor;
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
        $list_question_visitor_answer = SurveyQuestion::where(['survey_id' => $survey_id])
            ->with(['survey_visitor_question_answers' => function ($query) use ($survey_visitor) {
                $query->where('survey_visitor_id', $survey_visitor->survey_visitor_id)->with(['survey_answer']);
            }])->orderBy('order', 'asc')->get();

        return $list_question_visitor_answer;
    }

    /**
     * Delete survey visitor function
     *
     * @param [type] $survey_visitor_id
     * @return void
     */
    public function actionDeleteSurveyVisitor($survey_visitor_id)
    {
        SurveyVisitor::where('survey_visitor_id', $survey_visitor_id)->deleteWithTrack();
    }

    /**
     * @param [type] $survey_id
     * @param [type] $items
     * @param [type] $number_page
     * @return void
     */
    public function createSurveyQuestion($survey_question)
    {
        $survey_question->save();
        return $survey_question;
    }

    /**
     * @param [type] $items
     * @param [type] $survey_question
     * @return void
     */
    public function createSurveyAnswer($survey_question, $data)
    {
        $list_survey_answer = $survey_question->survey_answers()->createMany($data);
        return $list_survey_answer;
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
        $survey_question->survey_question_branch_conditions()->createMany(array_map(function ($condition) use ($survey_question, $question_branch) {
            return [
                'survey_question_id' => $survey_question['survey_question_id'],
                'branch_answer_id' => $question_branch[$condition['question_branch_id']][$condition['survey_answer_id']]['survey_answer_id'],
                'branch_question_id' => $question_branch[$condition['question_branch_id']][$condition['survey_answer_id']]['survey_question_id'],
            ];
        }, $branch_condition));
    }

    /**
     * Get validation rule function
     *
     * @param [type] $validation_rule_id
     * @return void
     */
    public function getValidationRule($validation_rule_id)
    {
        $validation_rule = DB::table('validation_rules')
            ->select('validation_rule_id')
            ->where('view_name', $validation_rule_id)->get();
        return $validation_rule;
    }

    /**
     * Save validation rule function
     *
     * @param [type] $validationRule
     * @return void
     */
    public function saveValidationRule($validationRule)
    {
        $validationRule->save();
    }

}
