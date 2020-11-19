<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MJCSN | Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="/plugins/wSelect/wSelect.css">
  <link rel="stylesheet" href="/dist/css/css.css">
  <link rel="stylesheet" href="/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="/dist/css/style.css">
  <link rel="stylesheet" href="/dist/css/ionicons.min.css">
  <link rel="stylesheet" href="/css/material.css">
  <link rel="stylesheet" href="/css/material-icons.css">
  <link rel="stylesheet" href="/css/util.css">
  <link rel="stylesheet" href="/css/common.css">
  <link rel="stylesheet" href="/css/survey/include/survey_detail_menu.css">
  @yield('css')

</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- header -->
  <nav class="main-header navbar navbar-expand navbar-dark navbar-danger">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-user mr-2"></i>{{ Auth::guard('admin')->user()->name }}
        </a>
      </li>
    </ul>
  </nav>
  <!-- sidebar -->
  <aside class="main-sidebar sidebar-light-danger elevation-2">
    <a href="{{route('BillPayController#billPayList')}}" class="brand-link navbar-light">
      <img src="{{asset('img/logo.png')}}" alt="MJCSN" class="brand-image">
      <span class="brand-text">MJCSN Admin</span>
    </a>
    <div class="sidebar">
      <nav class="mt-4">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="{{ route('CommunityUser#showList') }}" class="nav-link {{ is_active_route(route('CommunityUser#showList')) ? 'active' : '' }}">
              <i class="nav-icon fas fa-list"></i>
              <p>Community User List</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('BillPayController#billPayList') }}" class="nav-link {{ is_active_route(route('BillPayController#billPayList')) ? 'active' : '' }}">
              <i class="nav-icon fas fa-list"></i>
              <p>Introduce User List</p>
            </a>
          </li>
          <li class="nav-item has-treeview {{ is_active_route(route('AdminController#adminAccountList')) || is_active_route(route('RegisterController#registerPage')) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ is_active_route(route('AdminController#adminAccountList')) || is_active_route(route('RegisterController#registerPage')) ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Account Control<i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('AdminController#adminAccountList') }}" class="nav-link {{ is_active_route(route('AdminController#adminAccountList')) ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Account List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('RegisterController#registerPage') }}" class="nav-link {{ is_active_route(route('RegisterController#registerPage')) ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Create New Account</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="{{ route('user_show_survey_list') }}" class="nav-link {{ is_active_route(route('user_show_survey_list')) ? 'active' : '' }}">
              <i class="nav-icon fa fa-edit"></i>
              <p>アンケート</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('show_detail_survey_list') }}" class="nav-link {{ is_active_route(route('show_detail_survey_list')) ? 'active' : '' }}">
              <i class="nav-icon fa fa-book"></i>
              <p>アンケート集計</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('RegisterController#logout') }}" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Logout</p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>

  @if(session('flash_message'))
  <div class="callout callout-info flash-message-dialog">
    <p>{!!  nl2br(e(session('flash_message'))) !!} </p>
  </div>
  @endif

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <h1>@yield('content-header')</h1>
      </div>
    </section>
    <section class="content">
      <div class="container-fluid">
        @yield('content')
      </div>
    </section>
  </div>
</div>

<!-- jQuery -->
<script src="/plugins/jquery/jquery.min.js"></script>
<script src="/plugins/wSelect/wSelect.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/dist/js/demo.js"></script>
<!-- DataTables -->
<script src="/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<script src="/js/date.format.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script src="/js/jquery.validate.js"></script>
<script src="/js/i18/ja.js"></script>
<script src="/js/lib.js"></script>
<script src="/js/main.js"></script>
<script src="/js/material.js"></script>
<!-- survey answer -->
<script src="/plugins/amcharts/amcharts.js"></script>
<script src="/plugins/amcharts/serial.js"></script>
<script src="/plugins/amcharts/pie.js"></script>
<script src="/plugins/jsPDF/dist/jspdf.min.js"></script>
<script src="/plugins/jsPDF/libs/canvg_context2d/libs/rgbcolor.js"></script>
<script src="/plugins/jsPDF/libs/canvg_context2d/libs/StackBlur.js"></script>
<script src="/plugins/jsPDF/libs/canvg_context2d/canvg.js"></script>
<script src="/plugins/download/download.js"></script>
<!-- end survey answer -->
@yield('js')
@stack('ahead_javascript')
@stack('javascript')
</body>
</html>
