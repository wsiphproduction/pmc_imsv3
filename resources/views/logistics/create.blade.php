@extends('layouts.app')

@section('pagecss')
    <link href="{{env('APP_URL')}}/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="page-content">
    <div class="breadcrumbs">
        <h1><i class="fa fa-ship"></i> Create Schedule</h1>
        <ol class="breadcrumb">
            <li>
                <!-- <a href="/dashboard">Dashboard</a> -->
                <a href="{{ route('ims.dashboard') }}">Dashboard</a>
            </li>
            <li>
                <a href="{{env('APP_URL')}}/ims/purchasing">Purchasing</a>
            </li>
            <li class="active">Create Schedule</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="portlet light portlet-fit bordered">
                <div class="portlet-title">
                    <div class="alert alert-info">
                        Note! The last scheduled shipment will be tag as Full delivery
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <div class="table-responsive">  
                            <form autocomplete="off" method="post" action="{{ route('shipment.schedule.store') }}">
                                @csrf
                                <input type="hidden" name="poid" value="{{$poId}}">
                                <table class="table table-bordered" id="dynamic_field">
                                    <thead>
                                        <th>Supplier Committed Date</th>
                                        <th></th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input style="width: 100%;" name="dates[]" class="form-control form-control-inline datepicker" type="text" placeholder="From">
                                            </td>  
                                            <td><button type="button" name="add" id="add" class="btn btn-sm btn-success"><i class="fa fa-plus"></i></button></td>  
                                        </tr>
                                    </tbody>
                                </table>
                                <input type="hidden" name="rcounter" id="rcounter"/>
                                <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-save"></i> Submit</button>
                                <a href="{{ route('po.list') }}" class="btn btn-default pull-right" style="margin-right: 5px;"><i class="fa fa-backward"></i> Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2"></div>
    </div>

</div>
@endsection

@section('pagejs')
    <script src="{{env('APP_URL')}}/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>

    <script>
        $(document).on('click', '.datepicker', function(){
            $(this).datepicker({
                orientation: 'bottom',
                format: 'yyyy-mm-dd',
                autoclose: true,
                startDate: '+0d'
            }).focus();

            $(this).removeClass('datepicker'); 
        });

        $(document).ready(function(){

            var i = 0;  

            $('#add').click(function(){  
                i++; 
                 
               $('#rcounter').val(i);

                $('#dynamic_field').append('<tr id="row'+i+'" class="dynamic-added">'+
                    '<td>'+
                        '<input style="width: 100%;" name="dates[]" class="form-control form-control-inline datepicker" type="text" placeholder="From" required>'+
                    '</td>'+
                    '<td>'+
                    '<button type="button" name="remove" id="'+i+'" class="btn btn-sm btn-danger btn_remove">X</button>'+
                    '</td>'+
                '</tr>');

            });


            $(document).on('click', '.btn_remove', function(){  
               var button_id = $(this).attr("id");   
               $('#row'+button_id+'').remove();  
            });

        });
    </script>
@endsection