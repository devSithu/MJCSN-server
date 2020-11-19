@extends('layouts.app')
@section('content')
<div class="row">
  <div class="col-8 offset-2">
    <div class="card">
      <form action="{{ route('AdminController#update', $data->user_id) }}" method="post">
        @csrf
        <div class="card-body">
          <p class="login-box-msg"><b>Update Admin Information</b></p>
          <div class="form-group">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Full name" name="name" value="{{ old('name', $data->name)}}">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-user"></span>
                </div>
              </div>
            </div>
            @if($errors->has('name'))
              <span class="error text-red">
                <strong>{{ $errors->first('name') }}</strong>
              </span>
            @endif
          </div>
          <div class="form-group">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Email" name="email" value="{{ old('email', $data->email)}}">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope" ></span>
                </div>
              </div>
            </div>
            @if($errors->has('email'))
              <span class="error text-red">
                <strong>{{ $errors->first('email') }}</strong>
              </span>
            @endif
          </div>
        </div>
        <div class="card-footer clearfix">
          <button type="submit" class="btn btn-danger float-right">Update</button>
          <a class="btn btn-default" href="{{ route('AdminController#adminAccountList') }}">Back</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
