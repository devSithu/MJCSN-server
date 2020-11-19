<?php

namespace App\Http\Controllers\Survey;

use App\Contracts\Services\Survey\SurveyServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use App\Http\Requests\Survey\CreationSurveyStep1Request;
use App\Http\Requests\Survey\CreationSurveyStep2Request;
use App\Models\Config;
use App\Models\ValidationRule;
use CpsCSV;
use CpsForm;
use DB;
use Illuminate\Http\Request;
use Session;

class SurveyController extends Controller
{
    private $surveyService;

    /**
     * Create a new controller instance
     *
     * @param SurveyServiceInterface $surveyService
     */
    public function __construct(SurveyServiceInterface $surveyService)
    {
        $this->surveyService = $surveyService;
    }

    /**
     * Show survey list
     *
     * @return void
     */
    public function showList()
    {
        $list_survey = $this->surveyService->surveyList();
        return view('survey.list')->with(['list_survey' => $list_survey]);
    }

    /**
     * Show survey detail
     *
     * @param [type] $survey_id
     * @return void
     */
    public function showSurveyDetail($survey_id)
    {
        $survey = $this->surveyService->getSurveyCount($survey_id);
        if (empty($survey)) {
            Abort(404);
        }

        $data_type = $this->surveyService->getDataType();
        $data_type_array = $data_type->keyBy('data_type_id')->toArray();
        $list_survey_question = $this->surveyService->getSurveyQuestion($survey_id);
        $page = 0;
        foreach ($list_survey_question as $key => $question) {
            if ($key == 0) {
                $page = 1;
                $list_survey_question[$key]['split_page'] = 0;
            } else {
                if ($list_survey_question[$key]['page'] != $page) {
                    $page = $list_survey_question[$key]['page'];
                    $list_survey_question[$key]['split_page'] = 2;
                } else {
                    $list_survey_question[$key]['split_page'] = 1;
                }
            }
        }
        $total_visitor_accepted = 0;
        $total_visitor_answersed = count($survey->survey_visitors);
        $active = 1;

        array_map(function ($questions) use ($data_type_array) {
            if (array_key_exists($questions->data_type->data_type_id, $data_type_array)) {
                /**
                 * override the survey_question_datatype_name with the datatype_name from DataType.php.(eloquent file)
                 */
                $questions->data_type->name = $data_type_array[$questions->data_type->data_type_id]['name'];
            }
        }, $list_survey_question->all());

        return view('survey.detail', compact('survey', 'list_survey_question', 'total_visitor_accepted', 'total_visitor_answersed', 'active'));
    }

    /**
     * Show create survey form step1
     *
     * @return void
     */
    public function showCreateSurveyFormStep1()
    {
        CpsForm::start();

        return view('survey.new.step1');
    }

    /**
     * Validate create survey form step1
     *
     * @param CreationSurveyStep1Request $request
     * @return void
     */
    public function validateCreateSurveyFormStep1(CreationSurveyStep1Request $request)
    {
        CpsForm::keep();

        return redirect(route("user_show_create_survey_form2", [CpsForm::getInputName() => CpsForm::getFormId()]));
    }

    /**
     * Show create survey form step2
     *
     * @return void
     */
    public function showCreateSurveyFormStep2()
    {
        CpsForm::checkFormSessionOrFail(route("user_show_create_survey_form1"));

        Session::put('end_screen_message', CpsForm::input("end_screen_message"));
        Session::put('survey_name', CpsForm::input("name"));

        return view('survey.new.step2')->with(['survey_questions' => []]);
    }

    /**
     * Create survey
     *
     * @param CreationSurveyStep2Request $request
     * @return void
     */
    public function actionCreateSurvey(CreationSurveyStep2Request $request)
    {
        CpsForm::checkFormSessionOrFail(route("user_show_create_survey_form1"));

        if (empty(CpsForm::input("survey_questions"))) {
            return redirect(url()->previous());
        }

        if (Session::has('finish_screen_message')) {
            Session::forget('finish_screen_message');
        }

        if (Session::has('survey_name')) {
            Session::forget('survey_name');
        }

        DB::transaction(function () {
            $survey = $this->surveyService->createInformationSurvey();
            $this->_saveSurveyQuestion($survey);
        });

        return redirect(route("user_show_survey_list"));
    }

    /**
     * Download survey
     *
     * @param Request $request
     * @param [type] $survey_id
     * @return void
     */
    public function actionDownloadSurveyAnswerText(Request $request, $survey_id)
    {
        $list_survey_question = $this->surveyService->getSurveyQuestionList($request['survey_question_id'], $survey_id);
        $list_answer_text = $list_survey_question->survey_visitor_question_answers->pluck('content')->toArray();
        $survey = $this->surveyService->getSurvey($survey_id);
        $total_visitor_answersed = $survey->survey_visitors->count();
        //questions
        $csv_questions = "";
        $line = [
            'Q' . $list_survey_question->order,
            $list_survey_question->content,
        ];
        $csv_questions .= CpsCSV::toLineFromArray($line);
        $line = [
            '回答数 :' . count($list_survey_question->survey_visitor_question_answers),
            '無回答 :' . (($total_visitor_answersed) - count($list_survey_question->survey_visitor_question_answers)),
        ];
        $csv_questions .= CpsCSV::toLineFromArray($line);
        $line = ['', ''];
        $csv_questions .= CpsCSV::toLineFromArray($line);
        //csv header
        $headers = [0 => "回答"];
        $csv_header = CpsCSV::toLineFromArray($headers, 'header', []);
        // csv_body
        $csv_body = "";
        $i = 0;
        $textAreaIndex = [];
        foreach ($list_answer_text as $list) {
            $line = [$list];
            if ($list_survey_question->data_type_id == 2) {
                $textAreaIndex[] = $i;
            }
            $i++;
            $csv_body .= CpsCSV::toLineFromArray($line, null, $textAreaIndex);
        }

        $csv_text = mb_convert_encoding($csv_questions . $csv_header . $csv_body, 'SJIS-win', 'UTF-8');
        $filename = $survey->name . "_Q" . $list_survey_question->order . ".csv";

        return CpsCSV::download($csv_text, $filename);
    }

    /**
     * Survey csv download function
     * with query reduce
     *
     * @param $survey_id
     * @return download csv
     */
    public function actionSurveyCsvDownload($survey_id)
    {
        $survey = $this->surveyService->findSurvey($survey_id);
        $survey_questions = $this->surveyService->surveyQuestionAndAnswerForCsv($survey);
        $total_answered_visitor = $this->surveyService->surveyVisitorCountForCsv($survey);

        $is_question_has_other = [];
        $visitor_answer_date = [];

        //making csv header
        $headers_question = ["User number", "answerdate"];
        for ($i = 1; $i <= count($survey_questions); $i++) {
            $headers_question[] = 'Q' . $i;
            foreach ($survey_questions[$i - 1]->survey_answers as $survey_answer) {
                if ($survey_answer->is_other == true) {
                    $is_question_has_other[$survey_questions[$i - 1]->survey_question_id] = true;
                    $headers_question[] = 'Q' . $i . '_FA';
                } else {
                    $is_question_has_other[$survey_questions[$i - 1]->survey_question_id] = false;
                }
            }
        }
        $csv_header = CpsCSV::toLineFromArray($headers_question, 'header', []);

        //making csv body
        $csv_body = '';
        $answer_order = [];
        foreach ($survey_questions as $survey_question) {
            foreach ($survey_question->survey_answers as $key => $survey_answer) {
                $answer_order[$survey_question->survey_question_id][$survey_answer->survey_answer_id] = $key + 1;
            }
        }
        $survey_questions = $survey_questions->keyBy('survey_question_id')->toArray();

        if ($total_answered_visitor > 0) {
            //getting answered visitor data
            $column = ['community_user.user_number as visitorId',
                'survey_visitors.created_at as answerDate',
                'survey_visitor_question_answers.content as answerText',
                'survey_visitor_question_answers.survey_question_id as surveyQuestionId',
                'survey_visitor_question_answers.survey_answer_id as surveyAnswerId',
                'survey_answers.is_other as isOther'];

            $survey_visitors = $this->surveyService->getSurveyInfo($survey_id, $column);

            //change array format
            foreach ($survey_visitors as $key => $values) {
                $visitor_answer_date[$key] = $values[0]['answerDate'];
                $array = [];
                foreach ($values as $value) {
                    $array[$value['surveyQuestionId']][] = $value;
                }
                $survey_visitors[$key] = $array;
            }

            //making csv body data
            foreach ($survey_visitors as $visitor_id => $questions) {
                $line = [$visitor_id];
                array_push($line, format_datetime($visitor_answer_date[$visitor_id]));
                foreach ($survey_questions as $questions_id => $survey_question) {
                    if (!empty($questions[$questions_id])) {
                        if ($survey_question['data_type_id'] == 1 || $survey_question['data_type_id'] == 2) {
                            //for textbox and textarea
                            array_push($line, $questions[$questions_id][0]['answerText']);
                        } else if ($survey_question['data_type_id'] == 3 || $survey_question['data_type_id'] == 4) {
                            //for dropdown and radio
                            $answer_id = $questions[$questions_id][0]['surveyAnswerId'];
                            array_push($line, $answer_order[$questions_id][$answer_id]);
                            if ($is_question_has_other[$questions_id]) {
                                array_push($line, $questions[$questions_id][0]['answerText']);
                            }
                        } else {
                            //for checkbox
                            $checkbox_answer_id = [];
                            $checkbox_other_answer = '';
                            foreach ($questions[$questions_id] as $key => $checkbox_question) {
                                $answer_id = $checkbox_question['surveyAnswerId'];
                                array_push($checkbox_answer_id, $answer_order[$questions_id][$answer_id]);
                                if ($is_question_has_other[$questions_id] && $checkbox_question['answerText'] != null) {
                                    $checkbox_other_answer = $checkbox_question['answerText'];
                                }
                            }
                            asort($checkbox_answer_id);
                            array_push($line, implode(',', $checkbox_answer_id));
                            if ($is_question_has_other[$questions_id]) {
                                array_push($line, $checkbox_other_answer);
                            }
                        }
                    } else {
                        array_push($line, '');
                        if ($survey_question['data_type_id'] != 1 && $survey_question['data_type_id'] != 2) {
                            if ($is_question_has_other[$questions_id]) {
                                array_push($line, '');
                            }
                        }
                    }
                }
                $csv_body .= CpsCSV::toLineFromArray($line, null, []);
            }
        }
        $csv_text = mb_convert_encoding($csv_header . $csv_body, 'SJIS-win', 'UTF-8');
        $filename = $survey->name . ".csv";

        return CpsCSV::download($csv_text, $filename);
    }

    /**
     * Survey csv download function
     *
     * @param [type] $survey_id
     * @return void
     */
    public function actionSurveyQuestionCsvDownload($survey_id)
    {
        $survey = $this->surveyService->findSurvey($survey_id);
        $csv_header = ["no", "question"];
        $max_answer_choices = $this->surveyService->maxAnswerChoices($survey_id);
        for ($i = 1; $i <= $max_answer_choices; $i++) {
            $csv_header[] = $i;
        }
        $csv_header = CpsCSV::toLineFromArray($csv_header, 'header', []);

        //making csv body
        $csv_body = '';
        $question_and_choices = $this->surveyService->questionChoiceSurvey($survey_id);
        $question_answer_arr = [];
        foreach ($question_and_choices as $question) {
            $question_answer_arr[$question->survey_question_id]['question'] = $question->question_text;
            if ($question->answer_choice) {
                $question_answer_arr[$question->survey_question_id]['answers'][] = $question->answer_choice;
            }
        }
        $count = 1;
        foreach ($question_answer_arr as $question_answer) {
            $line = [];
            $line[] = 'Q' . $count++;
            $line[] = $question_answer['question'];
            if (isset($question_answer['answers'])) {
                foreach ($question_answer['answers'] as $answer) {
                    $line[] = $answer;
                }
            }
            $csv_body .= CpsCSV::toLineFromArray($line, null, []);
        }
        $csv_text = mb_convert_encoding($csv_header . $csv_body, 'SJIS-win', 'UTF-8');
        $filename = $survey->name . "_question.csv";
        return CpsCSV::download($csv_text, $filename);
    }

    /**
     * Delete function
     *
     * @param Request $request
     * @return void
     */
    public function actionDeleteSurvey(Request $request)
    {
        $list_survey = $this->surveyService->deleteSurveyList();
        $list_survey_count = count($list_survey);
        $deleteKey = array_search($request['survey_id'], $list_survey) + 1;

        $this->surveyService->forceDeleteSurvey($request['survey_id']);
        return redirect(request('redirect') ?: route("user_show_survey_list"))
            ->with('flash_message', config('constants.SURVEY_DELETED_MSG'))
            ->withInput();
    }

    /**
     * Edit survey function
     *
     * @param Request $request
     * @param [type] $survey_id
     * @return void
     */
    public function userSettingEditingSurvey(Request $request, $survey_id)
    {
        $survey = $this->surveyService->getSurvey($survey_id);
        return view('survey.edit.setting_step1_edit')->with(["survey" => $survey]);
    }

    /**
     * Edit survey step1 function
     *
     * @param CreationSurveyStep1Request $request
     * @param [type] $survey_id
     * @return void
     */
    public function actionEditSurveyFormStep1(CreationSurveyStep1Request $request, $survey_id)
    {
        CpsForm::keep();
        DB::transaction(function () {
            $survey = $this->surveyService->createInformationSurvey();
        });
        return redirect(route("user_show_edit_survey_form2", ['survey_id' => $survey_id, CpsForm::getInputName() => CpsForm::getFormId()]));
    }

    /**
     * Show edit survey step2 function
     *
     * @param [type] $survey_id
     * @return void
     */
    public function showEditSurveyFormStep2($survey_id)
    {
        CpsForm::checkFormSessionOrFail(route("user_setting_editing_survey", ['survey_id' => $survey_id]));
        Session::put('finish_screen_message', CpsForm::input("end_screen_message"));
        Session::put('survey_name', CpsForm::input("name"));

        $survey_questions = $this->surveyService->getSurveyQuestionBranch(CpsForm::input("survey_id"));
        $page = 0;
        foreach ($survey_questions as $key => $question) {
            if ($key == 0) {
                $page = 1;
                $survey_questions[$key]['split_page'] = 0;
            } else {
                if ($survey_questions[$key]['page'] != $page) {
                    $page = $survey_questions[$key]['page'];
                    $survey_questions[$key]['split_page'] = 2;
                } else {
                    $survey_questions[$key]['split_page'] = 1;
                }
            }
            $survey_questions[$key]['choices'] = implode(PHP_EOL, $question->survey_answers->where('is_other', false)->pluck('content')->toArray());
            $survey_questions[$key]['choices_branch'] = implode(PHP_EOL, $question->survey_answers->pluck('content')->toArray());
            $survey_questions[$key]['allow_other'] = count($question->survey_answers->where('is_other', true)->pluck('content')->toArray()) ? true : false;
            $survey_questions[$key]['required'] = false;
            $survey_questions[$key]['validation_rule_id'] = '';
            foreach ($question->survey_question_validation_rules as $validation_rule) {
                if ($validation_rule['validation_rule_id'] == ValidationRule::RULE_REQUIRED['validation_rule_id']) {
                    $survey_questions[$key]['is_required'] = true;
                } else if ($validation_rule['validation_rule_id'] == ValidationRule::RULE_MAX_LENGTH['validation_rule_id']) {
                    $survey_questions[$key]['max_length'] = true;
                } else {
                    $survey_questions[$key]['validation_rule_id'] = $validation_rule['validation_rule_id'];
                }
            }
            $answer_exclusion = $question->survey_answers->where('is_exclusion', true)->first();
            $survey_questions[$key]['is_exclusion'] = $answer_exclusion ? $answer_exclusion->survey_answer_id : 0;
        }

        return view('survey.new.step2')->with([
            'survey_questions' => $survey_questions,
            'survey_id' => CpsForm::input("survey_id"),
            'page_title' => 'アンケート編集',
        ]);
    }

    /**
     * Edit survey function
     *
     * @param [type] $survey_id
     * @return void
     */
    public function actionEditSurvey($survey_id)
    {
        $surveyVisitor = $this->surveyService->getSurveyVisitor($survey_id);
        if (count($surveyVisitor) > 0) {
            return redirect(route('url_user_show_survey_detail', ['survey_id' => $survey_id]));
        }

        if (empty(CpsForm::input("survey_questions"))) {
            return redirect(url()->previous());
        }

        if (Session::has('finish_screen_message')) {
            Session::forget('finish_screen_message');
        }

        if (Session::has('survey_name')) {
            Session::forget('survey_name');
        }

        DB::transaction(function () use ($survey_id) {
            $survey = $this->surveyService->getSurvey($survey_id);
            $this->_saveSurveyQuestion($survey);
        });

        return redirect(route("url_user_show_survey_detail", ['survey_id' => $survey_id]));
    }

    /**
     * Show preview function
     *
     * @param Request $request
     * @return void
     */
    public function showPreviewSurvey(Request $request)
    {
        Session::put('data_survey', $request->all());
        Session::put('finish_screen_message', CpsForm::input("end_screen_message"));
        Session::put('survey_name', CpsForm::input("name"));
        return redirect(route('user_show_preview', ['page' => Config::NEXT_PAGE_SURVEY_QUESTION]))->withInput();
    }

    /**
     * Show preview function
     *
     * @param [type] $page
     * @return void
     */
    public function showPreview($page)
    {
        $data = Session::get('data_survey');

        if (!isset($data['survey_questions'])) {
            return view('survey.preview.preview_error');
        }

        $survey_question = $data['survey_questions'];
        $first_page = 1;
        $number_page = 1;
        $tmp_question = [];

        foreach ($survey_question as $key => $items) {
            if ($key == 0) {
                $number_page = 1;
            } else {
                if (isset($items['branch_condition'])) {
                    $number_page++;
                } else {
                    if (!isset($items['split_page']) || $items['split_page'] == 2 || is_array($items['split_page'])) {
                        $number_page++;
                    }
                }
            }
            $survey_question[$key]['page'] = $number_page;
            if ($survey_question[$key]['page'] == 1) {
                $tmp_question[] = $items;
            }
        }

        $count_tmp_question = count($tmp_question);
        Session::put('preview_question', $survey_question);
        $survey_name = (Session::has('survey_name')) ? Session::get('survey_name') : '';

        return view('survey.preview.preview')->with([
            'questions' => $tmp_question,
            'total_page' => $number_page,
            'page' => $first_page,
            'list_answer' => '',
            'order_current_question' => $count_tmp_question,
            'is_next_page' => 1,
            'order_question' => 0,
            'survey_name' => $survey_name,
        ]);
    }

    /**
     * Show next preview function
     *
     * @param Request $request
     * @param [type] $page
     * @return void
     */
    public function showNextPreviewSurvey(Request $request, $page)
    {
        Session::put('data_page_' . $request->page, $request->all());
        return redirect(route('preview_next_page', ['page' => $page]))->withInput();
    }

    /**
     * Show next preview function
     *
     * @param [type] $page
     * @return void
     */
    public function showNextPreview($page)
    {
        $data = Session::get('data_page_' . ($page - Config::NEXT_PAGE_SURVEY_QUESTION));
        $list_answer = $data['list_answer'];
        $survey_name = '';

        if ($data['page'] >= $data['total_page']) {
            return redirect(route('preview_finish_page_survey_question'))->withInput();
        }

        $order_question = $data['order_current_question'];
        if (isset($data['answer_question'])) {
            $list_answer .= $this->getListAnswer($data['answer_question']);
        }

        if (Session::has('preview_question')) {
            $survey_questions = Session::get('preview_question');
        }

        if (Session::has('survey_name')) {
            $survey_name = Session::get('survey_name');
        }

        $page = $data['page'];
        while ($page <= $data['total_page']) {
            $page = $page + Config::NEXT_PAGE_SURVEY_QUESTION;
            $next_page_question = $this->_checkQuestionBranchAndGetListQuestion($survey_questions, $page, $list_answer);

            if (count($next_page_question) > 0 || ($page == $data['total_page'])) {
                break;
            }
        }

        if (empty($next_page_question) && $page == $data['total_page']) {
            return redirect(route('preview_finish_page_survey_question'))->withInput();
        }

        $count_tmp_question = $order_question + count($next_page_question);
        return view('survey.preview.preview')->with([
            'questions' => $next_page_question,
            'total_page' => $data['total_page'],
            'page' => $page,
            'list_answer' => $list_answer,
            'order_current_question' => $count_tmp_question,
            'order_question' => $order_question,
            'survey_name' => $survey_name,
        ]);
    }

    /**
     * Answer list function
     *
     * @param [type] $answer_question
     * @return void
     */
    public function getListAnswer($answer_question)
    {
        $list_answer = '';
        foreach ($answer_question as $key => $question) {
            if (is_array($question)) {
                foreach ($question as $item) {
                    $list_answer .= $key . '@' . $item . ',';
                }
            } else {
                $list_answer .= $key . '@' . $question . ',';
            }
        }

        return $list_answer;
    }

    /**
     * Preview finish function
     *
     * @return void
     */
    public function showFinishPreviewSurvey()
    {
        foreach (Session::all() as $key => $value) {
            if (strpos($key, 'data_page_') !== false) {
                Session::forget($key);
            }
        }
        Session::forget('preview_question');
        Session::forget('data_survey');
        $finish_screen_message = (Session::has('finish_screen_message')) ? Session::get('finish_screen_message') : '';
        $survey_name = (Session::has('survey_name')) ? Session::get('survey_name') : '';

        return view('survey.preview.finish')->with([
            'finish_screen_message' => $finish_screen_message,
            'survey_name' => $survey_name,
        ]);
    }

    /**
     * Show survey list function
     *
     * @return void
     */
    public function showSurveyList()
    {
        $list_survey = $this->surveyService->surveyList();
        return view('survey.survey_list')->with(['list_survey' => $list_survey]);
    }

    /**
     * Show survey detail function
     *
     * @param [type] $survey_id
     * @return void
     */
    public function showSurveyDetailData($survey_id)
    {
        $survey = $this->surveyService->getSurveyCount($survey_id);
        if (empty($survey)) {
            Abort(404);
        }

        $data_type = $this->surveyService->getDataType();
        $data_type_array = $data_type->keyBy('data_type_id')->toArray();
        $list_survey_question = $this->surveyService->getSurveyQuestion($survey_id);
        $page = 0;
        foreach ($list_survey_question as $key => $question) {
            if ($key == 0) {
                $page = 1;
                $list_survey_question[$key]['split_page'] = 0;
            } else {
                if ($list_survey_question[$key]['page'] != $page) {
                    $page = $list_survey_question[$key]['page'];
                    $list_survey_question[$key]['split_page'] = 2;
                } else {
                    $list_survey_question[$key]['split_page'] = 1;
                }
            }
        }
        $total_visitor_accepted = 0;
        $total_visitor_answersed = count($survey->survey_visitors);
        $active = 1;

        array_map(function ($questions) use ($data_type_array) {
            if (array_key_exists($questions->data_type->data_type_id, $data_type_array)) {
                /**
                 * override the survey_question_datatype_name with the datatype_name from DataType.php.(eloquent file)
                 */
                $questions->data_type->name = $data_type_array[$questions->data_type->data_type_id]['name'];
            }
        }, $list_survey_question->all());

        return view('survey.survey_detail', compact('survey', 'list_survey_question', 'total_visitor_accepted', 'total_visitor_answersed', 'active'));
    }

    /**
     * Show survey visitor function
     *
     * @param [type] $survey_id
     * @return void
     */
    public function showSurveyVisitor($survey_id)
    {
        $survey = $this->surveyService->getSurveyCount($survey_id);
        if (empty($survey)) {
            Abort(404);
        }
        $active = 2;

        return view('survey.detail_visitor', compact('survey', 'active'));
    }

    /**
     * Get survey visitor function
     *
     * @param DatatableRequest $request
     * @param [type] $survey_id
     * @return void
     */
    public function getSurveyVisitorList(DatatableRequest $request, $survey_id)
    {
        $visitors = $this->surveyService->searchForVisitorInSurvey($survey_id, $request);
        $total = $visitors->count();
        $visitors = $visitors->slice($request->start ?: 0, $request->length ?: 5)->values();

        return response_json($visitors, [
            'draw' => ($request->draw ?: 0) + 1,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
        ]);
    }

    /**
     * Show detail answer visitor function
     *
     * @param [type] $survey_id
     * @param [type] $user_number
     * @return void
     */
    public function showDetailAnswerVisitor($survey_id, $user_number)
    {
        $visitor = $this->surveyService->getVisitor($user_number);
        $survey_visitor = $this->surveyService->surveyVisitor($survey_id, $visitor);
        $list_question_visitor_answer = $this->surveyService->questionVisitorAnswer($survey_id, $survey_visitor);

        foreach ($list_question_visitor_answer as $survey_data) {
            if ($survey_data->data_type_id == 2) {
                foreach ($survey_data->survey_visitor_question_answers as $answer) {
                    $answer->content = preg_replace("/\r\n/", '<br>', $answer->content);
                }
            }
        }
        return view('survey.detail_answer_visitor')->with([
            'page_title' => $survey_visitor->survey->name . ' 回答者詳細',
            'visitor' => $visitor,
            'answer_datetime' => date('Y-m-d H:i:s', strtotime($survey_visitor->created_at)),
            'list_question_visitor_answer' => $list_question_visitor_answer,
            'survey_visitor_id' => $survey_visitor->survey_visitor_id,
        ]);
    }

    /**
     * Delete survey visitor function
     *
     * @param [type] $survey_id
     * @param [type] $survey_visitor_id
     * @return void
     */
    public function actionDeleteSurveyVisitor($survey_id, $survey_visitor_id)
    {
        $this->surveyService->actionDeleteSurveyVisitor($survey_visitor_id);
        return redirect(request('redirect') ?: route("QB::Survey#show_visitor_list", ['survey_id' => $survey_id]))
            ->with('flash_message', config('constants.SURVEY_DELETED_MSG'))
            ->withInput();
    }

    /**
     * Show question and answer function
     *
     * @param [type] $survey_id
     * @return void
     */
    public function showSurveyQandA($survey_id)
    {
        $survey = $this->surveyService->getSurveyCount($survey_id);
        if (empty($survey)) {
            Abort(404);
        }

        $list_survey_question = $this->surveyService->getSurveyQuestion($survey_id);
        $total_visitor_answersed = count($survey->survey_visitors);
        $active = 3;
        return view('survey.survey_question_and_answer', compact('survey', 'list_survey_question', 'total_visitor_answersed', 'active'));
    }

    /**
     * Save survey question function
     *
     * @param [type] $survey
     * @return void
     */
    private function _saveSurveyQuestion($survey)
    {
        $number_page = 1;
        $question_branch = [];
        $this->surveyService->forceDeleteSurveyQuestion($survey);
        foreach (CpsForm::input("survey_questions") as $key => $items) {
            if ($key == 0) {
                $number_page = 1;
            } else {
                if (isset($items['branch_condition'])) {
                    $number_page++;
                } else {
                    if (!isset($items['split_page']) || $items['split_page'] == 2 || is_array($items['split_page'])) {
                        $number_page++;
                    }
                }
            }

            $survey_question = $this->surveyService->createSurveyQuestion($survey['survey_id'], $items, $number_page);
            if ($items['data_type_id'] != Config::QUESTION_TEXT && $items['data_type_id'] != Config::QUESTION_TEXTAREA) {
                $question_branch[$key] = $this->surveyService->createSurveyAnswer($items, $survey_question);
            }

            if (isset($items['branch_condition']) && is_array($items['branch_condition'])) {
                $this->surveyService->createQuestionBranch($items['branch_condition'], $survey_question, $question_branch);
            }

            if (isset($items['validation_rule_id'])) {
                $this->surveyService->validationRuleById($items, $survey_question);
            }

            if (isset($items['required'])) {
                $this->surveyService->validationRuleByRequired($survey_question);
            }

            if (isset($items['max_length'])) {
                $this->surveyService->validationRuleByMaxlength($items, $survey_question);
            }
        }
    }

    /**
     * Question branch get list function
     *
     * @param [type] $survey_questions
     * @param [type] $page
     * @param [type] $list_answer
     * @return void
     */
    private function _checkQuestionBranchAndGetListQuestion($survey_questions, $page, $list_answer)
    {
        $next_page_question = [];
        foreach ($survey_questions as $key => $question) {
            if ($question['page'] == $page) {
                $explode_list_answer = $list_answer != '' ? explode(",", $list_answer) : [];
                if (isset($question['branch_condition']) && count($question['branch_condition']) > 0) {
                    foreach ($question['branch_condition'] as $condition) {
                        if (in_array($condition['question_branch_id'] . '@' . $condition['survey_answer_id'], $explode_list_answer)) {
                            $next_page_question[] = $question;
                            break;
                        }
                    }
                } else {
                    $next_page_question[] = $question;
                }
            }
        }
        return $next_page_question;
    }
}
