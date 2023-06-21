@extends('layouts.app')

@section('pagecss')
<link href="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
<link href="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
<link href="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
<link href="{{env('APP_URL')}}/assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />

<link href="{{env('APP_URL')}}/assets/global/plugins/bootstrap-sweetalert/sweetalert.css" rel="stylesheet" type="text/css" />
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
                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light bordered">
                                <div class="portlet-title" id="title">
                                    <div class="caption">
                                        <i class="fa fa-truck font-blue-hoki"></i>
                                        <span class="caption-subject font-blue-hoki bold uppercase"> Audit Logs</span>
                                    </div>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <th>Log Id</th>
                                        <th>PO #</th>
                                        <th>Action</th>
                                        <th>Log Date</th>
                                        <th>User</th>
                                        <th>Affected Field</th>
                                        <th>Old Value</th>
                                        <th>New Value</th>
                                        <th>Module</th>
                                    </thead>
                                    <tbody id="logs_tbl">
                                        @forelse($logs as $l)
                                            <tr>
                                                <td>{{ $l->id }}</td>
                                                <td>{{ $l->poId }}</td>
                                                <td>{{ $l->action }}</td>
                                                <td>{{ $l->log_date }}</td>
                                                <td>{{ $l->users }}</td>
                                                <td>{{ $l->affected_field }}</td>
                                                <td>
                                                    @if($l->action == 'UPDATE')
                                                        {{ $l->old_value}}
                                                    @endif
                                                </td>
                                                <td>{{ $l->new_value}}</td>
                                                <td>{{ $l->field }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8"><center>No Logs for this day</center></td>
                                            </tr> 
                                        @endforelse
                                    </tbody>
                                </table>
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

<script src="{{env('APP_URL')}}/assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js" type="text/javascript"></script>

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

            $.ajax({
                type: "GET",
                url: "/filter/logs",
                data: $('#form').serialize(),
                success: function( response ) {
                   $('#logs_tbl').html(response);
                    
                }
            });
        });
    </script>

@endsection