<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="{{ asset('assets/bootstrap/favicon.ico') }}">

  <title>{{ config('app.name') }} | @yield('title')</title>

  <!-- Bootstrap core CSS -->
  <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

  <!-- css custom card -->
  <link href="{{ asset('css/card_custom.css') }}" rel="stylesheet">


  {{-- dataTables --}}
  <link href="{{ asset('assets/datatables/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
  {{--   <link href="{{ asset('css/dataTables.uikit.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/uikit.min.css') }}" rel="stylesheet"> --}}

  {{-- SweetAlert2 --}}
  <script src="{{ asset('assets/sweetalert2/sweetalert2.min.js') }}"></script>
  <link href="{{ asset('assets/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">

  <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
  <link href="{{ asset('assets/bootstrap/css/ie10-viewport-bug-workaround.css') }}" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="{{ asset('assets/bootstrap/css/navbar-fixed-top.css') }}" rel="stylesheet">

  <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
  <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
  <script src="{{ asset('assets/bootstrap/js/ie-emulation-modes-warning.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('assets/font-awesome/css/font-awesome.min.css')}}" />
  <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
<link rel="stylesheet" href="{{ asset('assets/bootstrap-datepicker-master/bootstrap-datepicker3.css') }}">

<link rel="stylesheet" href="{{ asset('css/animate.css') }}">
  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

  <!--modal merah danger-->
  <style>
    body {
      font-family: 'Helvetica';
      font-weight: bold;
      background-color: #eeeeee;
      margin-left: 20px;
      margin-right: 20px;
    }

    @media (prefers-color-scheme: light) {
  body {
    font-family: 'Helvetica';
      font-weight: bold;
      background-color: #eeeeee;
      margin-left: 20px;
      margin-right: 20px;
  }
}

@media (prefers-color-scheme: dark) {
  body {
    font-family: 'Helvetica';
      font-weight: bold;
      background-color: #0a0a09;
      margin-left: 20px;
      margin-right: 20px;
  }

  .navbar {
  border:none;
  background-color: #1b1c1c;
}

}

    textarea {
      max-width: 100%;
    }

    .modal-header-danger {
      color: #fff;
      padding: 9px 15px;
      border-bottom: 1px solid #eee;
      background-color: #d9534f;
      -webkit-border-top-left-radius: 5px;
      -webkit-border-top-right-radius: 5px;
      -moz-border-radius-topleft: 5px;
      -moz-border-radius-topright: 5px;
      border-top-left-radius: 5px;
      border-top-right-radius: 5px;
    }
    
  </style>
  @yield('style')
</head>

<body>

  <!-- Fixed navbar -->
  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
        aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/"> {{ config('app.name') }}</a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">

      @include('layouts.header')

    </div>
    <!--/.nav-collapse -->
  </nav>

  <div id="loader"></div>


  <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
  @yield('content')


  <!-- Bootstrap core JavaScript
    ================================================== -->
  <!-- Placed at the end of the document so the pages load faster -->
  <script src="{{ asset('assets/jquery/jquery-2.2.3.min.js') }}"></script>
  <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('js/jquery.maskMoney.js') }}" type="text/javascript"></script>

  <script type="text/javascript" src="{{ asset('js/my.js') }}"></script>


  {{-- countertext --}}
  <script src="{{ asset('js/waypoints.min.js')}}"></script>
  <script src="{{ asset('js/countertext/jquery.counterup.min.js') }}"></script>

  {{-- dataTables --}}
  <script src="{{ asset('assets/dataTables/js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('assets/dataTables/js/dataTables.bootstrap.min.js') }}"></script>
  {{--   <script src="{{ asset('js/dataTables.uikit.min.js')}}"></script> --}}

  {{-- Validator --}}
  <script src="{{ asset('assets/validator/validator.min.js') }}"></script>

  <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
  <script src="{{ asset('assets/bootstrap/js/ie10-viewport-bug-workaround.js') }}"></script>

  <script src="{{ asset('js/phoneshope.js') }}"></script>

  <script src="{{ asset('js/date_time.js') }}"></script>

  <script src="{{ asset('js/dashboard/dashboard.js') }}"></script>

    <script src="{{ asset('js/jquery.pos.js') }}"></script>

    <script src="{{ asset('assets/Datejs-master/build/date.js') }}"></script>

  <!-- PACE -->
<script src="{{ asset('js/pace/pace.min.js') }}"></script>

<script src="{{ asset('assets/bootstrap-datepicker-master/bootstrap-datepicker.min.js')}}"></script>



  @yield('footer')

  <script>
    $(window).on("load", function () {
      $("#loader").fadeOut("slow");
    });
    $(document).ajaxStart(function () {
      // Show image container
      $("#loader").show();
    });
    $(document).ajaxComplete(function () {
      // Hide image container
      $("#loader").hide();
    });
  </script>

</body>

</html>
