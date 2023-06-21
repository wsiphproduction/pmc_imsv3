@extends('layouts.app')

@section('pagecss')
    <link href="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.css rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />

    <link href="{{env('APP_URL')}}/login/css/main1.css" rel="stylesheet" />

    <style type="text/css">
        td {
            font-size: 12px !important;
        }
    </style>
@endsection

@section('content')
<div class="page-container">
    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <!-- BEGIN CONTENT BODY -->
        <!-- BEGIN PAGE CONTENT BODY -->
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="s01">
                        <form id="form">
                            @csrf
                            <div class="inner-form">
                                <div class="input-field first-wrap">
                                    <input class="form-control date-picker" data-date-format="mm-dd-yyyy" size="16" type="text" value="" name="from" placeholder="From" />
                                </div>
                                <div class="input-field first-wrap">
                                    <input class="form-control date-picker" data-date-format="mm-dd-yyyy" size="16" type="text" value="" name="to" placeholder="To" />
                                </div>
                                <div class="input-field third-wrap">
                                    <button class="btn-search" type="submit"><i class="fa fa-filter"></i> Generate</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- BEGIN PAGE CONTENT INNER -->
                <div class="page-content-inner">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN SAMPLE TABLE PORTLET-->
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-truck font-blue-hoki"></i>
                                        <span class="caption-subject font-blue-hoki bold uppercase">Upcoming Deliveries</span>
                                    </div>
                                </div>
                                <center><img style="display:none;" id='loader' src="{{env('APP_URL')}}/assets/layouts/layout3/img/loading.gif') }}"></center>

                                <div class="portlet-body">
                                    <div id="data_tbl"></div>
                                </div>
                            </div>
                            <!-- END SAMPLE TABLE PORTLET-->
                        </div>
                    </div>
                </div>
                <!-- END PAGE CONTENT INNER -->
            </div>
        </div>
        <!-- END PAGE CONTENT BODY -->
        <!-- END CONTENT BODY -->
    </div>
    <!-- END CONTENT -->
</div>
@endsection

@section('pagejs')
    <script src="{{env('APP_URL')}}/assets/global/scripts/datatable.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/pages/scripts/table-datatables-buttons.min.js" type="text/javascript"></script>

    <script src="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });
        });

        $('#form').submit(function(e){
            e.preventDefault();
            $("#loader").show();
            $.ajax({
                type: "GET",
                url: "/reports/deliveries",
                data: $('#form').serialize(),
                success: function( response ) {
                    $("#loader").hide();
                    $('#data_tbl').html(response);
                    
                }
            });
        });
    </script>
@endsection