@extends('layouts.app')

@section('pagecss')
    <link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('login/css/main2.css') }}" rel="stylesheet" />
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
                                    <select data-live-search="true" data-live-search-style="startsWith" class="selectpicker form-control" name="supplier">
                                        <option value='0'>-- Select Supplier --</option>
                                        @foreach($data as $d)
                                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                                        @endforeach
                                    </select>
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
                                        <i class="fa fa-gift font-blue-hoki"></i>
                                        <span class="caption-subject font-blue-hoki bold uppercase">Payments per Supplier</span>
                                    </div>
                                </div>
                                <center><img style="display:none;" id='loader' src="{{ asset('assets/layouts/layout3/img/loading.gif') }}"></center>

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
    <script src="{{ asset('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/pages/scripts/table-datatables-buttons.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js') }}" type="text/javascript"></script>

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
                url: "/reports/supplier",
                data: $('#form').serialize(),
                success: function( response ) {
                    $("#loader").hide();
                    $('#data_tbl').html(response);     
                }
            });
        });
    </script>
@endsection