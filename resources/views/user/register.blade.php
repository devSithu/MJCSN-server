@extends('layouts.app')
@section('content')
<div class="row">
  <div class="col-8 offset-2">
    <div class="card">
      <form method="POST" action="{{route('RegisterController#register')}}" class="form-horizontal">
        @csrf
        <div class="card-body">
          <p class="login-box-msg"> <b>Register a new user</b></p>
          @if(session('status'))
            <p class="alert alert-danger">{{session('status')}}</p>
          @endif
          @if(Session::has('success'))
          <div class="alert alert-success" onclick="this.classList.add('hidden')">
            {{ Session::get('success') }}
          </div>
          @endif
          <div class="form-group">
            <div class="input-group">
              <input type="text" class="form-control" value="{{ old('name') }}" placeholder="Full name" name="name">
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
              <input type="email" class="form-control" value="{{ old('email') }}" placeholder="Email" name="email">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope"></span>
                </div>
              </div>
            </div>
            @if($errors->has('email'))
            <span class="error text-red">
              <strong>{{ $errors->first('email') }}</strong>
            </span>
            @endif
          </div>
          <div class="form-group">
            <div class="input-group">
              <input type="password" class="form-control" placeholder="Password" name="password">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            @if($errors->has('password'))
            <span class="error text-red">
              <strong>{{ $errors->first('password') }}</strong>
            </span>
            @endif
          </div>
          <div class="form-group">
            <div class="input-group">
              <input type="password" class="form-control" placeholder="Retype password"
                name="password_confirmation">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            @if($errors->has('password_confirmation'))
            <span class="error text-red">
              <strong>{{ $errors->first('password_confirmation') }}</strong>
            </span>
            @endif
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-danger">Register</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
