@extends('layouts.app')


<link rel="stylesheet" href="{{ url('css/main.css')}} ">

@section('pagecss')
    <link href="{{env('APP_URL')}}/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
@endsection


@section('content')
<div class="page-content">
    <!-- BEGIN BREADCRUMBS -->
    <div class="breadcrumbs">
        <h1>Logistics Page</h1>
        <ol class="breadcrumb">
            <li>
                <!-- <a href="/dashboard">Dashboard</a> -->
                <a href="{{ route('ims.dashboard') }}">Dashboard</a>
            </li>
            <li>
                <a href="{{env('APP_URL')}}/ims/logistics">Logistics</a>
            </li>
            <li class="active">Shipments</li>
        </ol>
    </div>
    <!-- END BREADCRUMBS -->

    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('po.list') }}" class="btn btn-default"><i class="fa fa-backward"></i> Back</a>
            @if(\App\PO::checkIfDelivered($poId) == '')
            <a href="{{env('APP_URL')}}/ims/shipment/edit/{{$poId}}" class="btn btn-primary pull-right"><i class="fa fa-edit"></i> Update Schedule</a>
            @endif
        </div>   
    </div>
    <br>

    <div class="row">
        <div class="col-md-12">
            <div class="portlet light portlet-fit bordered">
                <div class="portlet-body">
                    @foreach($waybills as $waybill) 
                          
                        <div class="panel panel-default">
                            <div style="background-color:@if($waybill->status == 'Delivered') #26C281; @else @if($waybill->waybill == 'shipment') #2F353B; @else #337ab7; @endif @endif ;color:#fff;font-weight:700;" class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$waybill->id}}" aria-expanded="true" aria-controls="collapseOne">
                                    {{ strtoupper($waybill->log_type) }} : {{strtoupper($waybill->waybill)}}
                                </a>
                                @if($waybill->status == 'Pending')
                                    <a class="pull-right addWaybill" href="#modalWaybill" data-toggle="modal" data-ship_id="{{$waybill->id}}"><i class="fa fa-plus"></i></a>
                                @endif
                                </h4>
                            </div>
                            

                            <div id="collapse{{$waybill->id}}" class="panel-collapse collapse {{$waybill->waybill == 'shipment' ? '' : 'in'}}" role="tabpanel" aria-labelledby="headingOne">
                                <div class="panel-body">
                                  <div class="mt-element-step">
                                        <div class="row step-line">

                                            <div class="col-md-3 mt-step-col first @if($waybill->portArrivalDate <> NULL) done @endif">
                                                <div class="mt-step-number bg-white">
                                                    @if($waybill->status == 'In-Transit' && $waybill->portArrivalDate == NULL)
                                                        <a href="#modalTransit" data-toggle="modal" data-ship_id="{{$waybill->id}}" class="transitBtn"><i class="fa fa-ship" style="margin-top: 8px;"></i></a>
                                                    @else
                                                        <i class="fa fa-ship" style="margin-top: 8px;"></i>
                                                    @endif
                                                </div>
                                                <div class="mt-step-title uppercase font-grey-cascade">In Transit</div>
                                                <div class="mt-step-content font-grey-cascade">{!! \App\logistics::inTransit($waybill->id) !!}</div>
                                                <div class="mt-step-content font-grey-cascade">{{$waybills[0]->eta}}</div>
                                                <div class="mt-step-content font-grey-cascade">{{$waybills[0]->port}}</div>
                                            </div>

                                            <div class="col-md-3 mt-step-col @if($waybill->customClearedDate <> NULL) done @endif">
                                                <div class="mt-step-number bg-white">
                                                    @if($waybill->portArrivalDate != '' && $waybill->customClearedDate == NULL)
                                                        <a href="#modalCustomClearing" data-toggle="modal" data-ship_id="{{$waybill->id}}" data-start="{{$waybill->customStartDate}}" class="customBtn"><i class="fa fa-industry" style="margin-top: 8px;"></i></a>
                                                    @else
                                                        <i class="fa fa-industry" style="margin-top: 8px;"></i>
                                                    @endif
                                                </div>
                                                <div class="mt-step-title uppercase font-grey-cascade">Custom Clearing</div>
                                                <div class="mt-step-content font-grey-cascade">{!! \App\logistics::customClearing($waybill->id) !!}</div>
                                                <div class="mt-step-content font-grey-cascade">{!! \App\logistics::customDateDiff($waybill->id) !!}</div>
                                                <div class="mt-step-content font-grey-cascade">{{$waybills[0]->ssdp}}</div>
                                                <div class="mt-step-content font-grey-cascade">{{$waybills[0]->broker_agent}}</div>
                                            </div>

                                            <div class="col-md-3 mt-step-col @if($waybill->status == 'For Pick-Up' || $waybill->status == 'Delivered') done @endif">
                                                <div class="mt-step-number bg-white">
                                                    @if($waybill->customClearedDate != NULL && $waybill->customClearedDate != NULL && $waybill->status == 'Custom Clearing')
                                                        <a href="#modalPickUp" data-toggle="modal" data-ship_id="{{$waybill->id}}" class="pickUpBtn"><i class="fa fa-cubes" style="margin-top: 8px;"></i></a>
                                                    @else
                                                        <i class="fa fa-cubes" style="margin-top: 8px;"></i>
                                                    @endif
                                                </div>
                                                <div class="mt-step-title uppercase font-grey-cascade">For Pick Up</div>
                                                {{-- <div class="mt-step-content font-grey-cascade">{!! \App\logistics::customClearing($waybill->id) !!}</div>
                                                <div class="mt-step-content font-grey-cascade">{!! \App\logistics::customDateDiff($waybill->id) !!}</div> --}}
                                                <div class="mt-step-content font-grey-cascade">{{$waybills[0]->tracker}}</div>
                                                <div class="mt-step-content font-grey-cascade">{{$waybills[0]->site}}</div>
                                                <div class="mt-step-content font-grey-cascade">{{$waybills[0]->ron}}</div>
                                                <div class="mt-step-content font-grey-cascade">{{$waybills[0]->pickup_date}}</div>
                                                <div class="mt-step-content font-grey-cascade">{{$waybills[0]->date_actual_pickup}}</div>
                                            </div>

                                            <div class="col-md-3 mt-step-col @if($waybill->status == 'Delivered') done @endif">
                                                <div class="mt-step-number bg-white">
                                                    @if($waybill->status == 'For Pick-Up')
                                                        <a href="#modalDelivered" data-toggle="modal" data-ship_id="{{$waybill->id}}" data-ltype="{{$waybill->log_type}}" data-poid="{{$waybill->poId}}" class="deliveredBtn"><i class="fa fa-truck" style="margin-top: 8px;"></i></a>
                                                    @else
                                                        <i class="fa fa-truck" style="margin-top: 8px;"></i>
                                                    @endif         </div>
                                                <div class="mt-step-title uppercase font-grey-cascade">Delivered</div>
                                                <div class="mt-step-content font-grey-cascade">{!! \App\logistics::actualDeliveryDate($waybill->id) !!}</div>
                                                <div class="mt-step-content font-grey-cascade">{{$waybills[0]->delivered_dt}}</div>
                                                <div class="mt-step-content font-grey-cascade">{{$waybills[0]->arrival_date}}</div>
                                                <div class="mt-step-content font-grey-cascade">{{$waybills[0]->destination_site}}</div>
                                                <div class="mt-step-content font-grey-cascade">{{$waybills[0]->designated_tracker}}</div>
                                                <div class="mt-step-content font-grey-cascade">{{$waybills[0]->delivery_date}}</div>
                                                <div class="mt-step-content font-grey-cascade">{{$waybills[0]->received_date}}</div>
                                            </div>
                                        </div>
                                        <p>Remarks : {{ \App\logistics::shipmentRemarks($waybill->id) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach                
                            </div>
                        </div>
                    </div>
                </div>  
            </div>

<div id="modalWaybill" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Create Waybill
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">

                <form autocomplete="off" method="post" action="{{ route('create.waybill') }}" class="form-horizontal" role="form">
                    @csrf
                    <input type="hidden" class="form-control" name="shipment_id" id="shipment_id">
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="dt">Actual Manufacturing Completion Date <i class="text-danger">*</i></label>
                        <div class="col-sm-9">
                            <input required style="width: 100%;" name="manufacture_dt" class="form-control form-control-inline date-picker" type="text" data-date-format="yyyy-mm-dd" data-date="+0d"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3" for="dt">Waybill Number<i class="text-danger">*</i></label>
                        <div class="col-sm-9">
                            <input required type="text" class="form-control" name="waybill">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3" for="dt">Departure Date <i class="text-danger">*</i></label>
                        <div class="col-sm-9">  
                            <input required style="width: 100%;" name="departure_dt" class="form-control form-control-inline date-picker" type="text" data-date-format="yyyy-mm-dd" data-date="+0d"/>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <span class='glyphicon glyphicon-check'></span> Create
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modalTransit" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                In-Transit
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form autocomplete="off" method="post" action="{{ url('/inTransit') }}" class="form-horizontal" role="form">
                    @csrf
                    <input type="hidden" name="shipment_id" id="transit_id">
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="dt">Port Arrival Date <i class="text-danger">*</i></label>
                        <div class="col-sm-9">
                            <input required style="width: 100%;" name="arrival_dt" class="form-control form-control-inline date-picker" type="text" data-date-format="yyyy-mm-dd" data-date="+0d"/>
                        </div>
                    </div>

                    {{-- <div class="form-group">
                        <label class="control-label col-sm-3" for="dt" title="Estimated Time Arrival">ETA<i class="text-danger">*</i></label>
                        <div class="col-sm-9">
                            <input required style="width: 100%;" name="eta" class="form-control form-control-inline" type="text">
                        </div>
                    </div> --}}

                    <div class="form-group">
                        <label class="control-label col-sm-3" title="Estimated Time Arrival" for="dt">ETA<i class="text-danger">*</i></label>
                        <div class="col-sm-9">
                            <input required style="width: 100%;" name="eta" class="form-control form-control-inline date-picker" type="text" data-date-format="yyyy-mm-dd" data-date="+0d"/>
                        </div>
                    </div>

                    <div class="port">
                    <label for="port" class="select">Select Port Name</label>
                    <select name="port" id="port">
                        <option value="">--Please choose an option--</option>
                        @foreach($choose as $selected) 
                        <option value= {{$selected->Portname}}>{{$selected->Portname}}</option>
                        @endforeach
                    </select>   
                        </div>

                         {{-- <div class="port" style="padding-left: 87px;">
                        <label for="port">Port</label>
                        <select name="port" id="port">
                            @foreach($choose as $selected)
                          <option value= {{$selected->Portname}}>{{$selected->Portname}}</option>
                          @endforeach
                        </select>
                        <br><br>
                    </div> --}}

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <span class='glyphicon glyphicon-check'></span> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- IN TRANSIT --}}


<div id="modalCustomClearing" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Custom Clearing
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <form autocomplete="off" method="post" action="{{ url('/customClearing')}}" class="form-horizontal" role="form">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="dt">Start Date :</label>
                        <div class="col-sm-9">
                            <input id="custom_id" name="shipment_id" type="hidden" class="form-control">
                            <div class="col-sm-9">
                                <input required style="width: 100%;" name="start" id="start_dt" class="form-control form-control-inline date-picker" type="text" data-date-format="yyyy-mm-dd" data-date="+0d"/>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3" for="dt">Cleared Date :</label>
                        <div class="col-sm-9">
                            <div class="col-sm-9">
                                <input style="width: 100%;" name="cleared" class="form-control form-control-inline date-picker" type="text" data-date-format="yyyy-mm-dd" data-date="+0d"/>
                            </div>
                        </div>
                    </div>

                    

                     <div class="form-group">
                    <label class="control-label col-sm-3" for="dt">SSDP :</label>
                    <div class="col-sm-9">
                        <div class="col-sm-9">
                            <input style="width: 100%;" name="ssdp" class="form-control form-control-inline" type="text" required>
                        </div>
                    </div>
                </div>

                <div class="port" style="margin-left: 46px;">
                    <label for="agent"><span class="select">Select Agent</span> :</label>
                    <select name="broker_agent" id="broker_agent">
                        <option value="#">--Please choose an option--</option>
                        @foreach($pick as $selected) 
                        <option value= {{$selected->broker_agent}}>{{$selected->broker_agent}}</option>
                        @endforeach
                    </select>   
                </div>
                </div>               


                <div class="modal-footer">
                    <button type="button" class="btn red btn-sm" data-dismiss="modal">
                        <span class='glyphicon glyphicon-remove'></span> Close
                    </button>
                    <button type="submit" class="btn blue btn-sm">
                        <span class='glyphicon glyphicon-check'></span> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- for pick up --}}
<div id="modalPickUp" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                For Pick-Up
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <form autocomplete="off" method="post" action="{{ url('/pickUp') }}" class="form-horizontal" role="form">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-sm-9" style="margin-left: 73px;">

                            <div class="form-group">
                                <label class="control-label col-sm-3" title="Designated Tracker" for="dt">Designated Tracker<i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <input required style="width: 100%;" name="tracker" class="form-control form-control-inline" type="text">
                                </div>
                            </div>    

                            <div class="form-group">
                                <label class="control-label col-sm-3" title="Destination Site" for="dt">Destination Site<i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <input required style="width: 100%;" name="site" class="form-control form-control-inline" type="text">
                                </div>
                            </div>    

                            <div class="form-group">
                                <label class="control-label col-sm-3" title="Release Order Number" for="dt">RON<i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <input required style="width: 100%;" name="ron" class="form-control form-control-inline" type="text">
                                </div>
                            </div>    

                           
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="dt">Date<i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <input required style="width: 100%;" name="pickup_date" class="form-control form-control-inline date-picker" type="text" data-date-format="yyyy-mm-dd" data-date="+0d"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-3" title="Date of Actual Pickup" for="dt">Date Pickup<i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <input required style="width: 100%;" name="date_actual_pickup" class="form-control form-control-inline date-picker" type="text" data-date-format="yyyy-mm-dd" data-date="+0d"/>
                                </div>
                            </div>   


                            <input id="pick_up_id" name="shipment_id" type="hidden" class="form-control">
                        </div>
                    </div>
                </div>
                        {{-- <div class="container-fluid">
                        <label for="ETA">ETA:</label>
                        <input type="text" id="eta" name="eta" class="form-control" required>
                    </div>   --}}

                <div class="modal-footer">
                    <button type="button" class="btn red btn-sm" data-dismiss="modal">
                        <span class='glyphicon glyphicon-remove'></span> Close
                    </button>
                    <button type="submit" class="btn blue btn-sm">
                        <span class='glyphicon glyphicon-check'></span> Yes, Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- end for pick up --}}

<div id="modalDelivered" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Shipment Delivered
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form autocomplete="off" method="post" action="{{ url('/delivered') }}" class="form-horizontal" role="form">
                    @csrf
                    <input type="hidden" name="shipment_id" id="delivered_id">
                    <input type="hidden" name="ltype" id="ltype">
                    <input type="hidden" name="poId" id="poid">
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="dt">Delivered Date <i class="text-danger">*</i></label>
                        <div class="col-sm-9">
                            <div class="col-sm-9">
                                <input required style="width: 100%;" name="delivered_dt" class="form-control form-control-inline date-picker" type="text" data-date-format="yyyy-mm-dd" data-date="+0d"/>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3" for="dt">Arrival Date <i class="text-danger">*</i></label>
                        <div class="col-sm-9">
                            <div class="col-sm-9">
                                <input required style="width: 100%;" name="arrival_date" class="form-control form-control-inline date-picker" type="text" data-date-format="yyyy-mm-dd" data-date="+0d"/>
                            </div>
                        </div>
                    </div>

                 
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="destination_site">Final Destination Site<i class="text-danger">*</i></label>
                        <div class="col-sm-9">
                            <div class="col-sm-9">
                                <input required style="width: 100%;" name="destination_site" class="form-control form-control-inline" type="text">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3" for="designated_tracker">Designated Tracker<i class="text-danger">*</i></label>
                        <div class="col-sm-9">
                            <div class="col-sm-9">
                                <input required style="width: 100%;" name="designated_tracker" class="form-control form-control-inline" type="text">
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-sm-3" for="delivery_date">Delivery Date<i class="text-danger">*</i></label>
                        <div class="col-sm-9">
                            <div class="col-sm-9">
                                <input required style="width: 100%;" name="delivery_date" class="form-control form-control-inline date-picker" type="text" data-date-format="yyyy-mm-dd" data-date="+0d"/>
                            </div>
                        </div>
                    </div>


                    
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="received_date">Received Date<i class="text-danger">*</i></label>
                        <div class="col-sm-9">
                            <div class="col-sm-9">
                                <input required style="width: 100%;" name="received_date" class="form-control form-control-inline date-picker" type="text" data-date-format="yyyy-mm-dd" data-date="+0d"/>
                            </div>
                        </div>
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <span class='glyphicon glyphicon-check'></span> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
<script src="{{env('APP_URL')}}/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>



<script>
    // ahead date
    
    // end ahead date




    $('.addWaybill').click(function(){
        $('#shipment_id').val($(this).data('ship_id'));
    });

    $('.transitBtn').click(function(){
        $('#transit_id').val($(this).data('ship_id'));
    });

    $('.customBtn').click(function(){
        if($(this).data('start') == ''){
            $('#start_dt').prop('readonly',false);
        } else {
             $('#start_dt').prop('readonly',true);
        }

        $('#start_dt').val($(this).data('start'));
        $('#custom_id').val($(this).data('ship_id'));
    });

    $('.pickUpBtn').click(function(){
        $('#pick_up_id').val($(this).data('ship_id'));
    });

    $('.deliveredBtn').click(function(){
        $('#delivered_id').val($(this).data('ship_id'));
        $('#ltype').val($(this).data('ltype'));
        $('#poid').val($(this).data('poid'));
    });
    

    $(document).ready(function(){
        $('#filter_po_list').submit(function(e){
            e.preventDefault();
            $.ajax({
                type: "GET",
                url: "/po_filter_list",
                data: $('#filter_po_list').serialize(),
                success: function( response ) {
                    $('#po_table').html(response);  
                }
            });
        });
    });
</script>
@endsection