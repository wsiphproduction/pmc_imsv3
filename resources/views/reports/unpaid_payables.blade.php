@extends('layouts.app')

@section('pagecss')
<link href="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
<link href="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
<link href="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
<link href="{{env('APP_URL')}}/assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />

<link href="{{env('APP_URL')}}/login/css/main1.css" rel="stylesheet" />

<style type="text/css">
    label {
        font-size: 10px;
    }
    td  {
        font-size: 12px !important;
    }
    th {
        font-family: cursive;
    }

    @page 
    {
        size: auto;
        margin: 5;
    }

</style>
@endsection

@section('content')
<div class="page-container">
    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <!-- BEGIN PAGE CONTENT BODY -->
        <div class="page-content">
            <div class="container-fluid">
                <!-- BEGIN PAGE CONTENT INNER -->
                <div class="page-content-inner">
                    <div class="row">
                        <div class="s01">
                            <form id="form">
                                @csrf
                                <div class="inner-form">
                                    <div class="input-field second-wrap">
                                        <select data-live-search="true" data-live-search-style="startsWith" class="selectpicker form-control" name="supplier">
                                            <option value=''>-- Select Supplier --</option>
                                            @foreach($data as $d)
                                                <option value="{{ $d->name }}">{{ $d->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
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

                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light bordered">
                                <div class="portlet-title" id="title">
                                    <div class="caption">
                                        <i class="fa fa-list font-blue-hoki"></i>
                                        <span class="caption-subject font-blue-hoki bold uppercase"> Unpaid Payables</span>
                                    </div>
                                    <a href="/excel/unpaid-payables" id="excel-btn" class="btn btn-sm green pull-right"><i class="fa fa-file-excel-o"></i> Export to Excel</a>
                                </div>

                                <center><img style="display: none;" id='loader' src="{{env('APP_URL')}}/assets/layouts/layout3/img/loading.gif') }}"></center>

                                <div id="data_unpaid_pay"></div>

                            </div>
                            <!-- END SAMPLE FORM PORTLET-->
                        </div>
                    </div>
                </div>
                <!-- END PAGE CONTENT INNER -->
            </div>
        </div>
        <!-- END PAGE CONTENT BODY -->
    </div>
    <!-- END CONTENT -->
</div>
@endsection

@section('pagejs')
<script src="{{env('APP_URL')}}/assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/pages/scripts/table-datatables-buttons.min.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>

<script type="text/javascript">
    $(document).ready(function(){
        
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
    });

    $.ajax({
        type: "GET",
        url: "/report/get_unpaid_data",
        success: function( response ) {
            $("#loader").hide();
            $('#data_unpaid_pay').html(response); 
        }
    });

    $('#form').submit(function(e){
        e.preventDefault();
        $("#loader").show();
        $.ajax({
            type: "GET",
            url: "/filter/unpaid_payment",
            data: $('#form').serialize(),
            success: function( response ) {
                $("#excel-btn").hide();
                $("#loader").hide();
                $('#data_unpaid_pay').html(response);
            }
        });
    });
</script>
@endsection