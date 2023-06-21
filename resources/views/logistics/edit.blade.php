@extends('layouts.app')

@section('pagecss')
    <link href="{{env('APP_URL')}}/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="page-content">
    <div class="breadcrumbs">
        <h1><i class="fa fa-ship"></i> Update Shipment Schedule</h1>
        <ol class="breadcrumb">
            <li>
                <!-- <a href="/dashboard">Dashboard</a> -->
                <a href="{{ route('ims.dashboard') }}">Dashboard</a>
            </li>
            <li>
                <a href="{{env('APP_URL')}}/ims/logistics">Logistics</a>
            </li>
            <li class="active">Update Shipment Schedule</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="portlet light portlet-fit bordered">
                <div class="portlet-body">
                    <div class="row">
                        <div class="table-responsive">  
                            <form autocomplete="off" method="post" action="{{ route('shipment.update') }}">
                                @csrf
                                <input type="hidden" name="poid" value="{{$poId}}">
                                <table class="table table-bordered" id="dynamic_field">
                                    <thead>
                                        <th>Shipment Type</th>
                                        <th>Waybill</th>
                                        <th>Commited Delivery Date</th>
                                        <th></th>
                                    </thead>
                                    <tbody>
                                        @foreach($shipments as $shipment)
                                            @if($shipment->status == 'Delivered')
                                                <tr>
                                                    <td>
                                                        <input readonly type="text" class="form-control text-uppercase" value="{{$shipment->log_type}}">
                                                    </td>
                                                    <td>
                                                        <input readonly type="text" class="form-control" value="{{$shipment->waybill}}">
                                                    </td>
                                                    <td><input readonly type="text" class="form-control" value="{{$shipment->actualDeliveryDate}}"></td>
                                                    <td></td>   
                                                </tr>
                                            @else
                                                <tr>
                                                    <td style="display: none;"><input type="text" value="{{$shipment->id}}" name="ship_id[]"></td>
                                                    <td>
                                                        <select name="log_type[]" id="" class="form-control text-uppercase">
                                                            <option @if($shipment->log_type == 'partial') selected @endif value="partial">Partial</option>
                                                            <option @if($shipment->log_type == 'full') selected @endif value="full">Full</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        @if($shipment->waybill == 'shipment')
                                                            <input readonly type="text" name="waybill[]" class="form-control" value="{{$shipment->waybill}}">
                                                        @else
                                                            <input type="text" name="waybill[]" class="form-control" value="{{$shipment->waybill}}">
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <input required type="text" style="width: 100%;" name="dates[]" class="form-control form-control-inline datepicker" value="{{$shipment->expectedDeliveryDate}}"/>
                                                    </td>
                                                    <td>
                                                        <a href="#" class="btn btn-danger" id="remove-shipment-sched" 
                                                            data-shipmentId="{{$shipment->id}}">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                        @if($loop->last)
                                                            <button type="button" name="add" id="add" class="btn btn-success"><i class="fa fa-plus"></i></button>
                                                        @endif
                                                    </td> 
                                                </tr>
                                            @endif

                                        @endforeach
                                    </tbody>
                                </table>
                                <input type="hidden" name="rcounter" id="rcounter" value="{{$counter+1}}" />
                                <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-save"></i> Save Changes</button>
                                <a href="{{ route('view.shipment.schedule',$poId) }}" class="btn btn-default pull-right" style="margin-right: 5px;"><i class="fa fa-backward"></i> Back</a>
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
    
    <script type="text/javascript">
        $(document).on('click', '.datepicker', function(){
            $(this).datepicker({
                orientation: 'bottom',
                format: 'yyyy-mm-dd',
                autoclose: true,
                startDate: '+0d'
            }).focus();

            $(this).removeClass('datepicker'); 
        });
    </script>

    <script>
        $(document).ready(function(){

            var i = $('#rcounter').val();  

            $('#add').click(function(){  
               i++;  
               $('#rcounter').val(i);

                $('#dynamic_field').append(
                    '<tr id="row'+i+'" class="dynamic-added">'+
                        '<td style="display:none;"><input type="text" value="new" name="ship_id[]"></td>'+
                        '<td>'+
                            '<select required name="log_type[]" class="form-control text-uppercase">'+
                                '<option value="">Choose One</option>'+
                                '<option value="partial">Partial</option>'+
                                '<option value="full">Full</option>'+
                            '</select>'+
                        '</td>'+
                        '<td><input readonly type="text" class="form-control" name="waybill[]" value="shipment"></td>'+
                        '<td><input required style="width: 100%;" name="dates[]" class="form-control form-control-inline datepicker" type="text"/></td>'+ 
                        '<td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td>'+
                    '</tr>');

            });

             $(document).on('click', '#remove-shipment-sched', function() {
                
                if( confirm("are you sure you want to delete this schedule? ") ) {

                    var x = $(this).attr('data-shipmentId');

                    $.ajaxSetup({
                        headers:
                        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                    });

                    $.ajax({
                        type:'DELETE',
                        url:'/ims/shipment/'+x  
                    }).done(function(data){
                        location.reload();
                    });
                }

            });


            $(document).on('click', '.btn_remove', function(){  
               var button_id = $(this).attr("id");   
               $('#row'+button_id+'').remove();  
            });

        });
    </script>
@endsection