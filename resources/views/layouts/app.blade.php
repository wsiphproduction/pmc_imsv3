<!DOCTYPE html>
<html lang="en">
<!-- BEGIN HEAD -->

<head>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="{{env('APP_URL')}}/assets/layouts/layout3/img/9-512.png">
  <meta charset="utf-8" />
  <title>PMC - IMS v3</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <meta content="Preview page of Metronic Admin Theme #3 for basic bootstrap tables with various options and styles" name="description" />
  <meta content="" name="author" />
  <!-- END LAYOUT FIRST STYLES -->

  <!-- BEGIN GLOBAL MANDATORY STYLES -->
  <link href="{{env('APP_URL')}}/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  <link href="{{env('APP_URL')}}/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
  <link href="{{env('APP_URL')}}/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <!-- END GLOBAL MANDATORY STYLES -->

  <!-- BEGIN THEME GLOBAL STYLES -->
  <link href="{{env('APP_URL')}}/assets/global/css/components-rounded.min.css" rel="stylesheet" id="style_components" type="text/css" />
  <link href="{{env('APP_URL')}}/assets/global/css/plugins-md.min.css" rel="stylesheet" type="text/css" />
  <!-- END THEME GLOBAL STYLES -->

  <!-- BEGIN THEME LAYOUT STYLES -->
  <link href="{{env('APP_URL')}}/assets/layouts/layout5/css/layout.min.css" rel="stylesheet" type="text/css" />
  <link href="{{env('APP_URL')}}/assets/layouts/layout5/css/custom.min.css" rel="stylesheet" type="text/css" />
  <link href="{{env('APP_URL')}}/assets/layouts/layout5/css/custom.min.css" rel="stylesheet" type="text/css" />
  <!-- END THEME LAYOUT STYLES -->

  <!-- BEGIN PAGE LEVEL PLUGINS -->
  <link href="{{env('APP_URL')}}/assets/global/plugins/bootstrap-toastr/toastr_notification.css" rel="stylesheet" type="text/css" />
  
  <style>
    .page-content {
      min-height: 860px !important;
    }

    /* cyrillic-ext */
    @font-face {
      font-family: 'Oswald';
      font-style: normal;
      font-weight: 300;
      /*src: url(https://fonts.gstatic.com/s/oswald/v33/TK3iWkUHHAIjg752FD8Ghe4.woff2) format('woff2');*/
      unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
    }

    /* cyrillic */
    @font-face {
      font-family: 'Oswald';
      font-style: normal;
      font-weight: 300;
      /*src: url(https://fonts.gstatic.com/s/oswald/v33/TK3iWkUHHAIjg752HT8Ghe4.woff2) format('woff2');*/
      unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
    }

    /* vietnamese */
    @font-face {
      font-family: 'Oswald';
      font-style: normal;
      font-weight: 300;
      /*src: url(https://fonts.gstatic.com/s/oswald/v33/TK3iWkUHHAIjg752Fj8Ghe4.woff2) format('woff2');*/
      unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
    }

    /* latin-ext */
    @font-face {
      font-family: 'Oswald';
      font-style: normal;
      font-weight: 300;
      /*src: url(https://fonts.gstatic.com/s/oswald/v33/TK3iWkUHHAIjg752Fz8Ghe4.woff2) format('woff2');*/
      unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
    }

    /* latin */
    @font-face {
      font-family: 'Oswald';
      font-style: normal;
      font-weight: 300;
      /*src: url(https://fonts.gstatic.com/s/oswald/v33/TK3iWkUHHAIjg752GT8G.woff2) format('woff2');*/
      unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }

    /* cyrillic-ext */
    @font-face {
      font-family: 'Oswald';
      font-style: normal;
      font-weight: 400;
      /*src: url(https://fonts.gstatic.com/s/oswald/v33/TK3iWkUHHAIjg752FD8Ghe4.woff2) format('woff2');*/
      unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
    }

    /* cyrillic */
    @font-face {
      font-family: 'Oswald';
      font-style: normal;
      font-weight: 400;
      /*src: url(https://fonts.gstatic.com/s/oswald/v33/TK3iWkUHHAIjg752HT8Ghe4.woff2) format('woff2');*/
      unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
    }

    /* vietnamese */
    @font-face {
      font-family: 'Oswald';
      font-style: normal;
      font-weight: 400;
      /*src: url(https://fonts.gstatic.com/s/oswald/v33/TK3iWkUHHAIjg752Fj8Ghe4.woff2) format('woff2');*/
      unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
    }

    /* latin-ext */
    @font-face {
      font-family: 'Oswald';
      font-style: normal;
      font-weight: 400;
      /*src: url(https://fonts.gstatic.com/s/oswald/v33/TK3iWkUHHAIjg752Fz8Ghe4.woff2) format('woff2');*/
      unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
    }

    /* latin */
    @font-face {
      font-family: 'Oswald';
      font-style: normal;
      font-weight: 400;
      /*src: url(https://fonts.gstatic.com/s/oswald/v33/TK3iWkUHHAIjg752GT8G.woff2) format('woff2');*/
      unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }

    /* cyrillic-ext */
    @font-face {
      font-family: 'Oswald';
      font-style: normal;
      font-weight: 700;
      /*src: url(https://fonts.gstatic.com/s/oswald/v33/TK3iWkUHHAIjg752FD8Ghe4.woff2) format('woff2');*/
      unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
    }

    /* cyrillic */
    @font-face {
      font-family: 'Oswald';
      font-style: normal;
      font-weight: 700;
      /*src: url(https://fonts.gstatic.com/s/oswald/v33/TK3iWkUHHAIjg752HT8Ghe4.woff2) format('woff2');*/
      unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
    }

    /* vietnamese */
    @font-face {
      font-family: 'Oswald';
      font-style: normal;
      font-weight: 700;
      /*src: url(https://fonts.gstatic.com/s/oswald/v33/TK3iWkUHHAIjg752Fj8Ghe4.woff2) format('woff2');*/
      unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
    }

    /* latin-ext */
    @font-face {
      font-family: 'Oswald';
      font-style: normal;
      font-weight: 700;
      /*src: url(https://fonts.gstatic.com/s/oswald/v33/TK3iWkUHHAIjg752Fz8Ghe4.woff2) format('woff2');*/
      unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
    }

    /* latin */
    @font-face {
      font-family: 'Oswald';  
      font-style: normal;
      font-weight: 700;
      /*src: url(https://fonts.gstatic.com/s/oswald/v33/TK3iWkUHHAIjg752GT8G.woff2) format('woff2');*/
      unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }

    /* The Modal (background) */
    #myModal1 {
      display: none;
      /* Hidden by default */
      position: fixed;
      /* Stay in place */
      z-index: 1;
      /* Sit on top */
      left: 0;
      top: 0;
      width: 100%;
      /* Full width */
      height: 100%;
      /* Full height */
      overflow: auto;
      /* Enable scroll if needed */
      background-color: rgb(0, 0, 0);
      /* Fallback color */
      background-color: rgba(0, 0, 0, 0.4);
      /* Black w/ opacity */
    }

    /* Modal Content/Box */
    #content {
      background-color: #fefefe;
      margin: 15% auto;
      /* 15% from the top and centered */
      padding: 20px;
      border: 1px solid #888;
      width: 45%;
      /* Could be more or less, depending on screen size */
    }

    /* The Close Button */
    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
    }

    .close:hover,
    .close:focus {
      color: black;
      text-decoration: none;
      cursor: pointer;
    }
  </style>
  @yield('pagecss')
  <!-- END PAGE LEVEL PLUGINS -->
</head>
<!-- END HEAD -->

<body>
  <div class="wrapper">
    <!-- BEGIN HEADER -->
    <header class="page-header navbar-fixed-top" style="margin-top: -1px;">
      @include('layouts.mega-menu')
      <div id="myModal1" class="modal">
      <!-- Modal content -->
      <div class="modal-content" id="content">
        <span class="close" id="close">&times;</span>
        <p style="font-size: 18px; font-weight:bold;">In exactly 1 hour the system will undergo maitenance! Please save your work!</p>
      </div>
    </div>
    <div style="padding-left:25px;padding-right:25px;margin-top:-1px">
      @if($reason)
      <div class="alert alert-danger alert-dismissable">
        <!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button> -->
        <span class="fa fa-exclamation"></span>
        <label aria-labelledby="notifications" id="notifications">{{ $reason }} </label>
        <label aria-labelledby="countdown" id="countdown" style="float:right; font-weight:bold">Time Remaining : </label>
        <label aria-labelledby="datetime" id="datetime" style="display:block">Shutdown Date : {{ $scheduledate }} {{ $scheduletime}} </label>
      </div>
      @else
      <label aria-labelledby="countdown" id="countdown" style="display:none; font-weight:bold">Time Remaining : </label>
      @endif
    </div>

    </header>
    <!-- END HEADER -->
    <div class="container-fluid">
      <br>
     
      @yield('content')


      <!-- BEGIN FOOTER -->
      <p class="copyright">&copy; {{ date('Y') }} Philsaga Mining Corporation &nbsp;|&nbsp;
        <a href="#">Indent Monitoring System v3</a>
      </p>
      <a href="#index" class="go2top">
        <i class="icon-arrow-up"></i>
      </a>
      <!-- END FOOTER -->
    </div>
  </div>

  <!-- BEGIN CORE PLUGINS -->
  <script src="{{env('APP_URL')}}/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
  <script src="{{env('APP_URL')}}/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
  <script src="{{env('APP_URL')}}/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
  <!-- END CORE PLUGINS -->

  <!-- BEGIN PAGE LEVEL PLUGINS -->
  <script src="{{env('APP_URL')}}/assets/global/plugins/moment.min.js" type="text/javascript"></script>
  <script src="{{env('APP_URL')}}/assets/global/plugins/morris/morris.min.js" type="text/javascript"></script>
  <script src="{{env('APP_URL')}}/assets/global/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
  <script src="{{env('APP_URL')}}/assets/global/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
  <!-- END PAGE LEVEL PLUGINS -->

  <!-- BEGIN THEME GLOBAL SCRIPTS -->
  <script src="{{env('APP_URL')}}/assets/global/scripts/app.min.js" type="text/javascript"></script>
  <!-- END THEME GLOBAL SCRIPTS -->

  <!-- BEGIN THEME LAYOUT SCRIPTS -->
  <script src="{{env('APP_URL')}}/assets/layouts/layout5/scripts/layout.min.js" type="text/javascript"></script>
  <script src="{{env('APP_URL')}}/assets/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
  <script src="{{env('APP_URL')}}/assets/global/scripts/quick-nav.min.js" type="text/javascript"></script>
  <!-- END THEME LAYOUT SCRIPTS -->

  <!-- PAGE LEVEL PLUGINS -->
  <script src="{{env('APP_URL')}}/assets/pages/scripts/toastr_notification.min.js" type="text/javascript"></script>
  @yield('pagejs')
  <!-- END PAGE LEVEL PLUGINS-->

  <script>
    @if(Session::has('notification'))
    var type = "{{ Session::get('notification.alert-type', 'info') }}";
    switch (type) {
      case 'info':
        toastr.info("{{ Session::get('notification.message') }}");
        break;

      case 'warning':
        toastr.warning("{{ Session::get('notification.message') }}");
        break;
      case 'success':
        toastr.success("{{ Session::get('notification.message') }}");
        break;
      case 'error':
        toastr.error("{{ Session::get('notification.message') }}");
        break;
    }
    @endif
    var modal = document.getElementById("myModal1");
    var tday = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    var tmonth = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    var shown = 0;
    var span = document.getElementById("close");
    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
      modal.style.display = "none";
    }

    function GetClock() {
      var d = new Date();
      var nday = d.getDay(),
        nmonth = d.getMonth(),
        ndate = d.getDate(),
        nyear = d.getFullYear();
      var nhour = d.getHours(),
        nmin = d.getMinutes(),
        nsec = d.getSeconds(),
        ap;
      var ohour = nhour + 1;
      if (nhour <= 9) nhour = "0" + nhour;
      if (nhour == 0) {
        ap = " AM";
        nhour = 12;
      } else if (nhour < 12) {
        ap = " AM";
      } else if (nhour == 12) {
        ap = " PM";
      } else if (nhour > 12) {
        ap = " PM";
        nhour -= 12;
      }

      if (nmin <= 9) nmin = "0" + nmin;
      if (nsec <= 9) nsec = "0" + nsec;

      var clocktext = "" + tday[nday] + ", " + tmonth[nmonth] + " " + ndate + ", " + nyear + " " + nhour + ":" + nmin + ":" + nsec + ap + "";
      // document.getElementById('clockbox').innerHTML = clocktext;
      var schedule = {!!json_encode($scheduledate) !!} + ' ' + {!!json_encode($scheduletime) !!};
      // dt = dt.replace(':00.0000000','');
      var mnth = nmonth + 1;
      var dte = ndate;
      if (mnth <= 9) mnth = "0" + mnth;
      if (dte <= 9) dte = "0" + dte;
      var curDateless1hour = nyear + '-' + mnth + '-' + dte + ' ' + ohour + ":" + nmin;
      var curDate = nyear + '-' + mnth + '-' + dte + ' ' + (ohour - 1) + ":" + nmin;
      // console.log(dt);
      // console.log(dd2);
      if (schedule == curDateless1hour && shown == 0) {
        shown = 1;
        //    alert("In exactly 1 hour the system will undergo maitenance! Please save your work.");

        modal.style.display = "block";
        return false;
      }
      if (schedule == curDate) {
        $.ajax({
          url: '{!! route('maintenance.application.systemDown') !!}',
          type: 'GET',
          async: false,
          success: function(response) {}
        });
      }
      // console.log(schedule);
      // console.log(curDate);
      if (schedule > curDate) {
        var TimeDiff = timeDiffCalc(new Date(schedule), new Date());
      } else {
        TimeDiff = "Maintenance is in progress!";
      }

      document.getElementById('countdown').innerHTML = "Time Remaining : " + TimeDiff;
    }
    GetClock();
    setInterval(GetClock, 1000);

    function timeDiffCalc(dateFuture, dateNow) {
      // console.log(dateNow);
      let diffInMilliSeconds = Math.abs(dateFuture - dateNow) / 1000;
      // calculate days
      const days = Math.floor(diffInMilliSeconds / 86400);
      diffInMilliSeconds -= days * 86400;

      // calculate hours
      const hours = Math.floor(diffInMilliSeconds / 3600) % 24;
      diffInMilliSeconds -= hours * 3600;

      // calculate minutes
      const minutes = Math.floor(diffInMilliSeconds / 60) % 60;
      diffInMilliSeconds -= minutes * 60;

      // calculate minutes
      const seconds = Math.floor(diffInMilliSeconds);
      diffInMilliSeconds -= seconds;
      // if(seconds > 0){

      let difference = '';
      if (days > 0) {
        difference += (days === 1) ? `${days} day, ` : `${days} days, `;
      }

      difference += (hours === 0 || hours === 1) ? `${hours} hour, ` : `${hours} hours, `;

      difference += (minutes === 0 || hours === 1) ? `${minutes} minute, ` : `${minutes} minutes, `;

      difference += (seconds === 0 || seconds === 1) ? `${seconds} seconds` : `${seconds} seconds`;

      return difference;
      // }
    }
  </script>

</body>

</html>