@extends('layouts.app')

@section('css')
  <link href="/css/survey/new/step1.css" rel="stylesheet">
  <link href="/bootstrap/plugin/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div ng-app="surveyApp" ng-controller="surveyFormController as ctrl">
          <div class="qb-form-container">
            <form id='form_edit_question' 
            class="form-horizontal qb-form with-checkbox" 
            action="{{ route('validate_create_survey_form1') }}" enctype="multipart/form-data"}}" 
            method="post">
              {!! csrf_field() !!}
              {{ CpsForm::renderFormIdField() }}
                <div>
                  <h4 class="qb-list-title">アンケート概要</h4>
                  <table class="table table-bordered">
                    <tr>
                      <td class="survey-label required">名称</td>
                      <td>
                        <div class="qb-form-inputs {{ CpsForm::getErrorClass($errors->has('name')) }}">
                          <input type="text" class="form-control" name="name" value="{{ CpsForm::oldOrSession('name') }}">
                          @if ($errors->has('name'))
                            {!! CpsForm::printErrorMessage($errors->first('name')) !!}
                          @endif
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="survey-label required">回答URL</td>
                      <td>
                        <div class="qb-form-inputs {{ CpsForm::getErrorClass($errors->has('url')) }}">
                          <div class="row">
                            <div class="url-table col-md-4">
                              {{ route('qvisitor_show_survey', ['']) }}/
                            </div>
                            <div class="url-table url-text col-md-8">
                              <input type="text" class="form-control" name="url" value="{{ CpsForm::oldOrSession('url') }}">
                            </div>
                          </div>
                          @if ($errors->has('url'))
                            {!! CpsForm::printErrorMessage($errors->first('url')) !!}
                          @endif
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="survey-label required">回答受付期間</td>
                      <td>
                        <div class="date-group mb10">
                          <label class="control-label qb-label-option-top qb-inline-label qb-inline-label-top mt00">開始</label>
                          <div class="qb-cal-group {{ CpsForm::getErrorClass($errors->has('open_date')) }}">
                            <input type="text" class="form-control qb-datepicker d-inline-block ml05 date" style="width: 150px;" id="form3-1" name="open_date"
                            value = "{{ CpsForm::oldOrSession('open_date') }}">
                            <i class="material-icons material-calendar-date">date_range</i>
                          </div>
                          <div class="qb-custom-select {{ CpsForm::getErrorClass($errors->has('open_hour')) }}">
                            <select class="form-control qb-custom-select-tar" name="open_hour" style="width: 70px; padding: 6px 12px">
                              @for ($i = 0; $i < 24; $i++)
                                <option value="{{ $i }}" @if(CpsForm::oldOrSession('open_hour') == $i) selected="selected" @endif>{{ $i < 10 ? 0 . $i : $i }}</option>
                              @endfor
                            </select>
                          </div>
                          <label class="control-label qb-inline-label mt00" style="width: 20px;">時</label>
                          <div class="qb-custom-select {{ CpsForm::getErrorClass($errors->has('open_minute')) }}">
                            <select class="form-control qb-custom-select-tar" name="open_minute" style="width: 70px; padding: 6px 12px">
                              @for ($i = 0; $i < 60; $i+=10)
                                <option value="{{ $i }}" @if(CpsForm::oldOrSession('open_minute') == $i) selected="selected" @endif>{{ $i < 10 ? 0 . $i : $i }}</option>
                              @endfor
                            </select>
                          </div>
                          <label class="control-label qb-inline-label mt00" style="width: 20px;">分</label>
                          @if ($errors->has('open_date'))
                            {!! CpsForm::printErrorMessage($errors->first('open_date')) !!}
                          @elseif ($errors->has('open_hour'))
                            {!! CpsForm::printErrorMessage($errors->first('open_hour')) !!}
                          @elseif ($errors->has('open_minute'))
                            {!! CpsForm::printErrorMessage($errors->first('open_minute')) !!}
                          @endif
                        </div>
                        <div class="date-group">
                          <label class="control-label qb-label-option-top qb-inline-label qb-inline-label-top mt00">終了</label>
                          <div class="qb-cal-group {{ CpsForm::getErrorClass($errors->has('end_date')) }}">
                            <input type="text" class="form-control qb-datepicker d-inline-block ml05 date" style="width: 150px;" id="form3-1" name="end_date"
                            value = "{{ CpsForm::oldOrSession('end_date') }}">
                            <i class="material-icons material-calendar-date">date_range</i>
                          </div>
                          <div class="qb-custom-select {{ CpsForm::getErrorClass($errors->has('end_hour')) }}">
                            <select class="form-control qb-custom-select-tar" name="end_hour" style="width: 70px; padding: 6px 12px">
                              @for ($i = 0; $i < 24; $i++)
                                <option value="{{ $i }}" @if(CpsForm::oldOrSession('end_hour') == $i) selected="selected" @endif>{{ $i < 10 ? 0 . $i : $i }}</option>
                              @endfor
                            </select>
                          </div>
                          <label class="control-label qb-inline-label mt00" style="width: 20px;">時</label>
                          <div class="qb-custom-select {{ CpsForm::getErrorClass($errors->has('end_minute')) }}">
                            <select class="form-control qb-custom-select-tar" name="end_minute" style="width: 70px; padding: 6px 12px">
                              @for ($i = 0; $i < 60; $i+=10)
                                <option value="{{ $i }}" @if(CpsForm::oldOrSession('end_minute') == $i) selected="selected" @endif>{{ $i < 10 ? 0 . $i : $i }}</option>
                              @endfor
                            </select>
                          </div>
                          <label class="control-label qb-inline-label mt00" style="width: 20px;">分</label>
                          @if ($errors->has('end_date'))
                            {!! CpsForm::printErrorMessage($errors->first('end_date')) !!}
                          @elseif ($errors->has('end_hour'))
                            {!! CpsForm::printErrorMessage($errors->first('end_hour')) !!}
                          @elseif ($errors->has('end_minute'))
                            {!! CpsForm::printErrorMessage($errors->first('end_minute')) !!}
                          @endif
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="survey-label">開始画面メッセージ</td>
                      <td>
                        <div class="qb-form-inputs {{ CpsForm::getErrorClass($errors->has('start_screen_message')) }}">
                          <textarea class="form-control" name="start_screen_message" style="resize: both">{{ old('start_screen_message', CpsForm::oldOrSession('start_screen_message')) }}</textarea>
                          @if ($errors->has('start_screen_message'))
                            {!! CpsForm::printErrorMessage($errors->first('start_screen_message')) !!}
                          @endif
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="survey-label">終了画面メッセージ</td>
                      <td>
                        <div class="qb-form-inputs {{ CpsForm::getErrorClass($errors->has('end_screen_message')) }}">
                          <textarea class="form-control" name="end_screen_message" style="resize: both">{{ old('end_screen_message', CpsForm::oldOrSession('end_screen_message')) }}</textarea>
                          @if ($errors->has('end_screen_message'))
                            {!! CpsForm::printErrorMessage($errors->first('end_screen_message')) !!}
                          @endif
                        </div>
                      </td>
                    </tr>
                  </table>
                </div>
                <div class="form-btn-triple-group">
                  <div class="btn-triple-group text-center">
                    <a href="{{ route('user_show_survey_list') }}" class="btn btn-default">キャンセル</a>
                    <button type="submit" class="btn btn-danger">次へ</button>
                  </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('js')
  <script src="/bootstrap/plugin/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
  <script src="/bootstrap/plugin/bootstrap-datepicker/locales/bootstrap-datepicker.ja.min.js" charset="UTF-8"></script>
  <script src="/js/survey/new/step1.js"></script>
@endsection
