<?php

namespace App\Http\Controllers\Form;

use DB;
use Route;
use CpsForm;
use Session;
use Response;
use Carbon\Carbon;
use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Contracts\Services\ActionLogServiceInterface;
use App\Http\Requests\Form\SurveyPageQuestionRequest;
use App\Contracts\Services\Form\SurveyServiceInterface;
use App\Http\Requests\Form\SurveyNextPageQuestionRequest;

class SurveyController extends Controller
{
    private $surveyService,$actionLogService;

    /**
     * Create a new controller instance
     *
     * @param SurveyServiceInterface $surveyService
     */
    public function __construct(SurveyServiceInterface $surveyService,ActionLogServiceInterface $actionLogService)
    {
        $this->surveyService = $surveyService;
        $this->actionLogService = $actionLogService;
    }

    /**
     * Get All Survey List from API
     *
     * @return void
     */
    public function getAllSurvey(Request $request)
    {
        $surveys = $this->surveyService->getallSurvey();
        // $this->getHeaderData($request);
        return Response::json($surveys);
    }

    /**
     * get header data
     *
     * @return void
     */
     public function getHeaderData($request)
     {
         
         $data = [
             'user_number' => $request->header('X-UserID'),
             'action' => $request->header('X-Action'),
             'action_at' => Carbon::now(),
             'parameter' => '-',
             'point' =>  "",
             'os' => $request->header('X-OS'),
             'os_version' => $request->header('X-OSVersion'),
             'app' => "MJCSN",
             'app_version' => $request->header('X-Version'),
         ];
         $this->actionLogService->createHeaderData($data);
 
     }

    /**
     * Get All Survey function
     *
     * @return void
     */
    public function allSurvey(Request $request)
    {
        $surveys = $this->surveyService->getallSurvey();
        $usernumber = $request->user_number ? $request->user_number : null;
        return view('form.survey.allsurvey')->with([
            'surveys' => $surveys,
            'user_number' => $usernumber,
        ]);
    }

    /**
     * Survey login function
     *
     * @return void
     */
    public function loginSurvey()
    {
        $survey = $this->h_survey;
        if (isset($this->h_communityuser)) {
            $communityuser = $this->h_communityuser;
            if (Session::has('survey_user_number_' . $survey->survey_id) && ($this->h_communityuser->user_number == Session::get('survey_user_number_' . $survey->survey_id))) {
                return redirect(route('qvisitor_create_survey_question', ['url' => Route::input('url')]))->withInput();
            } else {
                Session::forget('user_answer_question_' . $survey->survey_id);
                Session::forget('survey_user_number_' . $survey->survey_id);
                Session::forget('data_current_page_' . $survey->survey_id);
            }
            return view('form.survey.survey_login')->with([
                'survey' => $survey,
                'communityuser' => $communityuser,
            ]);
        }
        if (Session::has('survey_user_number_' . $survey->survey_id)) {
            return redirect(route('qvisitor_create_survey_question', ['url' => Route::input('url')]))->withInput();
        }
        return view('form.survey.survey_login')->with([
            'survey' => $survey,
        ]);
    }

    /**
     * Survey login function
     *
     * @param SurveyPageQuestionRequest $request
     * @return void
     */
    public function postLoginSurvey(SurveyPageQuestionRequest $request)
    {
        try {

            $survey = $this->h_survey;
            if (Session::has('survey_user_number_' . $survey->survey_id)) {
                return redirect(route('qvisitor_create_survey_question', ['url' => Route::input('url')]))->withInput();
            }

            $communityuser = $this->surveyService->getCommunityUserByUserNumber($request['user_number']);
            $user_answered_survey = $this->surveyService->getSurveyBySurveyId($survey->survey_id, $communityuser['user_number']);

            if (!empty($user_answered_survey)) {
                return view('form.survey.survey_login')->with(['survey' => $survey, 'user_answered' => 'このアンケートは回答済です。']);
            }
            Session::forget('user_answer_question_' . $survey->survey_id);
            Session::put('survey_user_number_' . $survey->survey_id, $communityuser['user_number']);

            return redirect(route('qvisitor_create_survey_question', ['url' => Route::input('url')]));
        } catch (\Exception $e) {
            return redirect(route('qvisitor_show_survey', ['url' => Route::input('url')]))->withInput();
        }
    }

    /**
     * Show question of survey
     *
     * @return void
     */
    public function showQuestionSurvey()
    {
        $number_page = Config::NEXT_PAGE_SURVEY_QUESTION;
        try {
            $survey = $this->h_survey;
            $total_page = $this->surveyService->totalPageofSurveyQuestion($survey['survey_id']);
            $list_survey_question = $this->surveyService->getSurveyQuestion($survey['survey_id'], $number_page);

            $usernumber = Session::get('survey_user_number_' . $survey->survey_id);
            $communityuser = $this->surveyService->communityUserInformation($usernumber);

            return view('form.survey.question_survey')->with([
                'questions' => $list_survey_question,
                'page_number' => $number_page,
                'total_page' => $total_page,
                'communityuser' => $communityuser,
            ]);

        } catch (\Exception $e) {
            return redirect(route('qvisitor_show_survey', ['url' => Route::input('url')]))->withInput();
        }
    }

    /**
     * Validate question function
     *
     * @param SurveyNextPageQuestionRequest $request
     * @return void
     */
    public function validateQuestion(SurveyNextPageQuestionRequest $request)
    {
        $survey_id = $this->h_survey->survey_id;
        Session::put('data_current_page_' . $survey_id, CpsForm::input());

        return redirect(route('qvisitor_next_page_survey_question', ['url' => Route::input('url')]))->withInput();
    }

    /**
     * Show question next page
     *
     * @return void
     */
    public function showQuestionSurveyNextPage()
    {
        $survey_id = $this->h_survey->survey_id;

        if (!Session::has('survey_user_number_' . $survey_id)) {
            return redirect(route('qvisitor_show_survey', ['url' => Route::input('url')]))->withInput();
        }

        $data = Session::get('data_current_page_' . $survey_id);
        $total_page = $data['total_page'];
        $this->_saveAnswerToSession($data, $survey_id);

        if ($data['page_number'] >= $total_page) {
            return redirect(route('qvisitor_finish_survey', ['url' => Route::input('url')]));
        }
        $list_question = null;
        $number_page = $data['page_number'];
        while ($number_page < $data['total_page']) {
            $number_page = $number_page + Config::NEXT_PAGE_SURVEY_QUESTION;
            $list_question = $this->checkQuestionBranchAndGetListQuestion($survey_id, $number_page);
            if (count($list_question) > 0 || $number_page == $total_page) {
                break;
            }
        }

        if ($number_page == $total_page && count($list_question) == 0) {
            return redirect(route('qvisitor_finish_survey', ['url' => Route::input('url')]));
        }

        if ($data['current_order'] == '') {
            $current_order = count($data['question_id']);
        } else {
            $current_order = $data['current_order'] + count($data['question_id']);
        }

        if (empty($data['user_number'])) {
            $communityuser = null;
        } else {
            $communityuser = $this->surveyService->communityUserInformation($data['user_number']);
        }
        return view('form.survey.question_survey')->with([
            'current_order' => $current_order,
            'questions' => $list_question,
            'page_number' => $number_page,
            'total_page' => $total_page,
            'communityuser' => $communityuser,
        ]);
    }

    /**
     * Finish survey function
     *
     * @return void
     */
    public function showFinishSurvey()
    {
        DB::beginTransaction();
        try {
            $survey = $this->h_survey;
            $survey_visitor_id = $this->surveyService->createSurveyVisitor($survey->survey_id, Session::get('survey_user_number_' . $survey->survey_id));

            if (Session::has('user_answer_question_' . $survey->survey_id)) {
                $other_answer = [];
                foreach (Session::get('user_answer_question_' . $survey->survey_id) as $key => $val) {
                    if (Session::has('answer_question_' . $key . '_other')) {
                        $other_answer = Session::get('answer_question_' . $key . '_other');
                        Session::forget('answer_question_' . $key . '_other');
                    }
                    $survey_question = $this->surveyService->findSurveyQuestion($key);
                    $this->surveyService->createAnswerOfVisitor($survey_question, $survey_visitor_id->survey_visitor_id, $val, $other_answer);
                }
            }

            Session::forget('user_answer_question_' . $survey->survey_id);
            Session::forget('survey_user_number_' . $survey->survey_id);
            Session::forget('data_current_page_' . $survey->survey_id);
            DB::commit();
            return view('form.survey.finish_survey')->with([
                'survey' => $survey,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Finish survey errors: ' . $e->getMessage());
            return redirect(route('qvisitor_show_survey', ['url' => Route::input('url')]))->withInput();
        }
    }

    /**
     * Save survey answer function
     *
     * @param [type] $data
     * @param [type] $survey_id
     * @return void
     */
    private function _saveAnswerToSession($data, $survey_id)
    {
        $array_answer_old = Session::get('user_answer_question_' . $survey_id);
        foreach ($data['question_id'] as $question_id) {
            if (!empty($array_answer_old[$question_id])) {
                unset($array_answer_old[$question_id]);
            }
            if (isset($data['answer_question_' . $question_id])) {
                $array_answer_old[$question_id] = $data['answer_question_' . $question_id];
            }
            if (isset($data['answer_question_' . $question_id . '_other'])) {
                $array_answer_other = $data['answer_question_' . $question_id . '_other'];
                Session::put(('answer_question_' . $question_id . '_other'), $array_answer_other);
            }
        }
        Session::forget('user_answer_question_' . $survey_id);
        Session::put(('user_answer_question_' . $survey_id), $array_answer_old);
    }

    /**
     * Check question branch and get list question function
     *
     * @param [type] $survey_id
     * @param [type] $number_page
     * @return void
     */
    private function checkQuestionBranchAndGetListQuestion($survey_id, $number_page)
    {
        $list_survey_question = $this->surveyService->getSurveyQuestion($survey_id, $number_page);
        $list_answer = [];
        if (Session::has('user_answer_question_' . $survey_id)) {
            foreach (Session::get('user_answer_question_' . $survey_id) as $key => $val) {
                $survey_question = $this->surveyService->findSurveyQuestion($key);
                if ($survey_question['data_type_id'] != \App\Models\Config::QUESTION_TEXT && $survey_question['data_type_id'] != \App\Models\Config::QUESTION_TEXTAREA) {
                    if (is_array($val)) {
                        foreach ($val as $value) {
                            $list_answer[] = $value;
                        }
                    } else {
                        $list_answer[] = $val;
                    }
                }
            }
        }

        foreach ($list_survey_question as $key => $list) {
            $result = [];
            if (count($list->survey_question_branch_conditions)) {
                $question_branch = $list->survey_question_branch_conditions->pluck('branch_answer_id')->toArray();
                $result = array_intersect($question_branch, $list_answer);
            }
            if (count($list->survey_question_branch_conditions) && (count($result) == 0)) {
                unset($list_survey_question[$key]);
                $array_answer = Session::get('user_answer_question_' . $survey_id);
                if (isset($array_answer[$list['survey_question_id']])) {
                    unset($array_answer[$list['survey_question_id']]);
                }
                Session::forget('user_answer_question_' . $survey_id);
                Session::put(('user_answer_question_' . $survey_id), $array_answer);
            }
        }

        return $list_survey_question;
    }
}
