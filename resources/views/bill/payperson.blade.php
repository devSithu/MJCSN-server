@extends('layouts.app')
@section('content')
<div class="row">
  <div class="col-8 offset-2">
    <div class="card">
      @if(session('success'))
        <p class="alert alert-success">{{ session('success') }}</p>
      @endif
      <div class="card-header table-responsive" id="introduceForm">
        <table id="introduceData" class="">
          <tr>
            <td>User Name</td>
            <td>&emsp;&emsp;:&emsp;&emsp;</td>
            <td>{{ $userData->user_name }}</td>
          </tr>
          <tr>
            <td>Operator</td>
            <td>&emsp;&emsp;:&emsp;&emsp;</td>
            <td>{{ $userData->career }}</td>
          </tr>
          <tr>
            <td>Phone No</td>
            <td>&emsp;&emsp;:&emsp;&emsp;</td>
            <td>{{ $userData->phone_number }}</td>
          </tr>
        </table>  
      <small class="float-right"></small>
      </div>
      <form method="post"  action="{{ route('BillPayController#payPersonBill') }}" id="introduceForm">
        @csrf
        <div class="card-body p-4">
          @foreach ($introducedUsers as $item)
            <div class="form-group">
              <label id='introduceName'>{{ $item['data']->user_name }}</label>
              <div class="input-group mb-4">
                <div class="input-group-prepend">
                  <span class="btn btn-danger" id="introduceName">{{ $userData->career }}</span>&emsp;
                </div>
                <input type="hidden" name="{{ $item['data']->introducer_user_number }}" value="{{ $item['data']->introducer_user_number }}">
                <input type="text" maxlength="2" class="s_number" name="{{ $item['data']->introducer_user_number }}_bill_one" id="billTextOne" value="{{ $item['one'] }}">&emsp;
                <input type="text" maxlength="4" class="s_number" name="{{ $item['data']->introducer_user_number }}_bill_two" id="billTextTwo" value="{{ $item['two'] }}">&emsp;
                <input type="text" maxlength="4" class="s_number" name="{{ $item['data']->introducer_user_number }}_bill_three" id="billTextTwo" value="{{ $item['three'] }}">&emsp;
                <input type="text" maxlength="4" class="s_number" name="{{ $item['data']->introducer_user_number }}_bill_four" id="billTextTwo" value="{{ $item['four'] }}">&emsp; 
                <input type="text" maxlength="4" class="s_number" name="{{ $item['data']->introducer_user_number }}_bill_five" id="billTextTwo" value="{{ $item['five'] }}">
              </div>
            </div>
          @endforeach
        </div>
        <div class="card-footer clearfix">
          <button type="submit" class="btn btn-danger float-right">Submit</button>
          <a class="btn btn-default" href="{{ route('BillPayController#billPayList') }}">Back</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
@section('js')
  <script src="/js/bill/payperson.js"></script>
@endsection
