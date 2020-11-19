@extends('layouts.app')
@section('css')
  <link rel="stylesheet" href="/css/survey/detail_answer_visitor.css">
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="qb-content-container">
          <div class="qb-detail-dashboard">
            <div class="profile">
              <figure class="thumb"></figure>
              <div class="pl15">
                <p class="usernumber">User Number : {{ $visitor->user_number }}</p>
                <p class="name">User Name : {{ $visitor->user_name }}　様</p>
                <p class="info">Answer Date : {{ $answer_datetime }}</p>
              </div>
            </div>
          </div>

          <h4 class="qb-align-center">回答内容</h4>
          <div class="qb-content-detail-answer">
            <div class="qb-list-option-right delete-button">
              <button type="button" class="btn-ic-delete btn btn-simple btn-fab btn-fab-mini" id="delete_next_button" data-toggle="modal" data-target="#delete_modal">
                <i class="material-icons">delete</i>
              </button>
            </div> 
            @foreach ($list_question_visitor_answer as $question)
              <div class="row content-answer">
                <div class="col-1 question-number">
                  Q{{ $question->order }}
                </div>
                <div class="col-11 content-answers">
                  <span>{{ $question->content }}</span>
                  </br>
                  <div class="mt05 mb10">
                    @if ($question->data_type_id >= 3 && $question->data_type_id <= 5)
                      @foreach ($question->survey_visitor_question_answers->sortBy('survey_answer_id') as $visitor_answer)
                        {{ $loop->first ? '' : ',' }}
                        {{ $visitor_answer->survey_answer->content }}
                        @if ($visitor_answer->survey_answer->content === 'その他')
                          : {{ $visitor_answer->content }}
                        @endif
                      @endforeach
                    @elseif (count($question->survey_visitor_question_answers) > 0)
                      @if (preg_match("/<br>/", $question->survey_visitor_question_answers->first()->content))
                        @foreach (explode('<br>', $question->survey_visitor_question_answers->first()->content) as $data)
                          {{ $data }} 
                          </br>
                        @endforeach
                      @else
                        {{ $question->survey_visitor_question_answers->first()->content }}
                      @endif
                    @endif
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
        <div class="modal qb-modal fade" id="delete_modal" tabindex="-1" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content qb-modal-content">
              <div class="modal-header">
                <h4 class="modal-title">回答の削除</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body qb-modal-body">
                <form class="form-horizontal qb-form" method="POST" 
                action="{{ route('user_delete_survey_visitor', ['survey_id'  => \Route::input('survey_id'), 'survey_visitor_id' => $survey_visitor_id]) }}">
                  {{ csrf_field() }}
                  {{ method_field('delete') }}
                  <i class="material-icons qb-warning-icon">error</i>
                  <div class="survey-qb-warning-box ">
                    <span>アンケートの回答を削除しますか？</span>
                  </div>
                  <div class="modal-footer">
                    <button data-bb-handler="cancel" type="button" class="btn btn-success" data-dismiss="modal">キャンセル</button>
                    <button data-bb-handler="confirm" type="submit" class="btn btn-danger" id="delete_button">確認</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section("js")
  <script src="/js/shared/scroll_block.js"></script>
@endsection
