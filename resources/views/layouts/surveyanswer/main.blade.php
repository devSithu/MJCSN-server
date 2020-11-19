<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MJCSN | Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="/css/material.css">
  <link rel="stylesheet" href="/css/material-icons.css">
  <link rel="stylesheet" href="/css/util.css">
  <link rel="stylesheet" href="/css/common.css">
  <link rel="stylesheet" href="/plugins/wSelect/wSelect.css">
  @yield('css')
</head>
<body class="hold-transition">
  <div class="wrapper survey-page" id="survey-answer-page">
    <nav class="navbar navbar-expand-md navbar-dark navbar-danger">
      <div class="container">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
              <img src="{{ url('/img/ic-mjcsn.png') }}" class="brand-image" alt="logo">
              <span class="brand-text">MJCSN</span>
            </a>
          </li>
        </ul>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item dropdown">
            <div class="dropdown-menu dropdown-menu-sm-left dropdown-menu-right">
              <a href="#" class="dropdown-item">View Profile</a>
              <a href="#" class="dropdown-item">Update Profile</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item dropdown-footer btn-danger" href="#" role="button">
                <i class="fas fa-sign-out-alt mr-2"></i>Log out
              </a>
            </div>
          </li>
        </ul>
      </div>
    </nav>
    <div class="container">
      @yield('content')
    </div>
  </div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="/plugins/jquery/jquery.min.js"></script>
<script src="/dist/js/adminlte.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/dist/js/demo.js"></script>
<script src="/plugins/wSelect/wSelect.min.js"></script>
<!-- DataTables -->
<script src="/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script src="/js/jquery.validate.js"></script>
<script src="/js/i18/ja.js"></script>
<script src="/js/lib.js"></script>
<script src="/js/main.js"></script>
<script src="/js/material.js"></script>
@yield('js')
@stack('ahead_javascript')
@stack('javascript')
</body>
</html>
