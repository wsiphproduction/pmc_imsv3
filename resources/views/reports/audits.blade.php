@extends('layouts.app')
@section('pagecss')
    <link href="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
    
@endsection
@section('content')


<!-- BEGIN SIDEBAR CONTENT LAYOUT -->

<!-- BEGIN PAGE HEADER-->

<div class="page-content">
    <!-- BEGIN BREADCRUMBS -->
    <div class="breadcrumbs">
        <h1>User <small>Actions</small></h1>
        <ol class="breadcrumb">
            <li>
                <!-- <a href="/dashboard">Dashboard</a> -->
                <a href="{{ route('ims.dashboard') }}">Dashboard</a>
            </li>
            <li>
                <a href="#">Reports</a>
            </li>
            <li class="active">User Actions</li>
        </ol>
    </div>
    <!-- END BREADCRUMBS -->
    <div class="row">
        <div class="col-md-12 well">
            <form autocomplete="off" id="form" class="form-inline">
                <div class="form-group">
                    <input style="width: 100%;" name="dateFrom" id="dateFrom" class="form-control form-control-inline date-picker" type="text" data-date-format="yyyy-mm-dd" data-date-end-date="+0d" value="{{ app('request')->input('from') }}" placeholder="Date From" />
                </div>
                <div class="form-group">
                    <input style="width: 100%;" name="dateTo" id="dateTo" class="form-control form-control-inline date-picker" type="text" data-date-format="yyyy-mm-dd" data-date-end-date="+0d" value="{{ app('request')->input('to') }}" placeholder="Date To" />
                </div>
                <div class="form-group">
                    <select required name="userid" id="userid" class="form-control select2">
                        <option value="0">-- Select All -- </option>
                        @foreach($users as $user)
                        <option value="{{ $user['id'] }}">{{ $user['domainAccount'] }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('audit-logs') }}" class="btn default">Reset</a>

            </form>
        </div>
    </div>
        <div class="clearfix"></div>
        <div class=" row">

            <div class="col-md-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="fa fa-user font-dark"></i>
                            <span class="caption-subject bold uppercase">Records</span>
                        </div>
                        <div class="tools"> </div>
                    </div>
                   

                    <div class="portlet-body">
                        <br>

                        <table class="table table-striped table-hover" id="sample_101">
                            <thead>
                                <tr>
                                    <th style="width: 10%">Model</th>
                                    <th style="width: 7%">Action</th>
                                    <th style="width: 8%">User</th>
                                    <th style="width: 10%">Date</th>
                                    <th>Old Values</th>
                                    <th>New Values</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($audits as $audit)
                                <tr>
                                    <td style="width: 10%">{{ $audit->auditable_type }} (id: {{ $audit->auditable_id }})</td>
                                    <td style="width: 7%">{{ $audit->event }}</td>
                                    @if($audit->user)
                                    <td style="width: 8%">{{ $audit->user->name }}</td>
                                    @else
                                    <td style="width: 8%">No User Name</td>
                                    @endif

                                    <td style="width: 10%">{{ $audit->created_at }}</td>
                                    <td>
                                        @foreach($audit->old_values as $attribute => $value)
                                        <b>{{ $attribute }}</b></br>
                                        {{ $value }}
                                        @endforeach
                                        <!-- <table class="table">
                                                    @foreach($audit->old_values as $attribute => $value)
                                                    <tr>
                                                        <td><b>{{ $attribute }}</b></td>
                                                        <td>{{ $value }}</td>
                                                    </tr>
                                                    @endforeach
                                                </table> -->
                                    </td>
                                    <td>
                                        @foreach($audit->new_values as $attribute => $value)
                                        <b>{{ $attribute }}</b></br>
                                        {{ $value }}
                                        @endforeach
                                        <!-- <table class="table">
                                                    @foreach($audit->new_values as $attribute => $value)
                                                    <tr>
                                                        <td><b>{{ $attribute }}</b></td>
                                                        <td>{{ $value }}</td>
                                                    </tr>
                                                    @endforeach
                                                </table> -->
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
                <!-- END EXAMPLE TABLE PORTLET-->
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>
</div>
<!-- END SIDEBAR CONTENT LAYOUT -->
@endsection


@section('pagejs')
    <script src="{{env('APP_URL')}}/assets/global/scripts/datatable.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>

    <script src="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
  
  <script src="{{ url('plugins/datatables/datatable.js') }}" type="text/javascript"></script>
  <script src="{{ url('plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
  
  <script src="{{ url('plugins/datatables/table-datatables-buttons.js') }}" type="text/javascript"></script>

<script type="text/javascript">
    function getReportDetails() {
        const dateFrom = urlParams.get('dateFrom')
        const dateTo = urlParams.get('dateTo')
        var userid = urlParams.get('userid')
        if (userid == null) {
            userid = 0
        }
        $('#dateFrom').val(dateFrom);
        $('#dateTo').val(dateTo);
        $('#userid').val(userid);

    }
    $(function() {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);

        const dateFrom = urlParams.get('dateFrom')
        const dateTo = urlParams.get('dateTo')
        var userid = urlParams.get('userid')
        if (userid == null) {
            userid = 0
        }
        $('#dateFrom').val(dateFrom);
        $('#dateTo').val(dateTo);
        $('#userid').val(userid);

        // let dateInterval = getQueryParameter('dateFrom','dateTo','userid');

    });

    function getQueryParameter(datefrom, dateto, userid) {



        // const url = window.location.href;
        // name = name.replace(/[\[\]]/g, "\\$&");
        // const regex = new RegExp("[?&]" + datefrom + "(=([^&#]*)|&|#|$)"),
        //     results = regex.exec(url);

        // var dateFrom = decodeURIComponent(results[0].replace(/\+/g, " "));
        // var dateTo = decodeURIComponent(results[1].replace(/\+/g, " "));
        // var userid = decodeURIComponent(results[2].replace(/\+/g, " "));
        // alert(dateFrom);
        // alert(dateTo);
        // alert(userid);
        // // alert(decodeURIComponent(results[1].replace(/\+/g, " ")));
        // if (!results) return null;
        // if (!results[2]) return '';
        // return decodeURIComponent(results[2].replace(/\+/g, " "));
    }
</script>
@endsection