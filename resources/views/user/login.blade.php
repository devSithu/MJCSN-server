
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MJCSN | Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo" style="background-color: #dd3546;">
      <a href="#" style="color: white;"><b>MJCSN</b> Admin</a>
    </div>

    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Sign in</p>

        <form action="{{ route('RegisterController#login') }}" method="post">
          @csrf
          @if(Session::has('status'))
          <div class="alert alert-danger" onclick="this.classList.add('hidden')">
            {{ Session::get('status') }}
          </div>
          @endif
          <div class="form-group">
            <div class="input-group">
              <input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}">
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
            @if ($errors->has('password'))
              <span class="error text-red">
                <strong>{{ $errors->first('password') }}</strong>
              </span>
            @endif
          </div>
          <div class="row">
            <div class="col-8">    
          </div>
          <div class="col-4">
            <input type="submit" value="Sign In" class="btn btn-danger btn-block">
          </div>
        </form>       
      </div>      
    </div>
  </div>

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>

</body>

</html>
