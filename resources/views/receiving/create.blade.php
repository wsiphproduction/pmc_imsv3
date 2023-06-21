@extends('layouts.app')

@section('pagecss')
    <link href="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="page-content">
    <!-- BEGIN BREADCRUMBS -->
    <div class="breadcrumbs">
        <h1>Create Delivery Receiving Report</h1>
        <ol class="breadcrumb">
            <li>
                <!-- <a href="/dashboard">Dashboard</a> -->
                <a href="{{ route('ims.dashboard') }}">Dashboard</a>
            </li>
            <li>
                <a href="{{env('APP_URL')}}/ims/purchasing">MCD</a>
            </li>
            <li class="active">Create DRR</li>
        </ol>
    </div>
    <!-- END BREADCRUMBS -->
                    
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <form autocomplete="off" action="{{ route('drr.store') }}" method="POST" id="formid" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="delivery_term" value="{{ $shipment->po_details->delivery_term }}">
                <input type="hidden" name="waybill" value="{{$shipment->waybill}}">
                <input type="hidden" name="poId" value="{{$shipment->poId}}">
                <input type="hidden" name="poNumber" value="{{$shipment->po_details->poNumber}}">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-ship font-red-sunglo"></i>
                            <span class="caption-subject font-red-sunglo bold uppercase">PO# {{ $shipment->po_details->poNumber }}</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Waybill <i class="font-red">*</i></label>
                                        <input readonly type="text" class="form-control input-lg" value="{{$shipment->waybill}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>DRR Number <i class="font-red">*</i></label>
                                        <input required type="text" class="form-control input-lg" name="drn">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>DRR Amount <i class="font-red">*</i></label>
                                        <input type="number" class="form-control text-right input-lg" name="drrAmount" id="drrAmount" step="0.01" value="0.00" max="{{ \App\PO::drrAmountBalance($shipment->poId) }}">
                                        <span class="text-danger">Remaining DRR Amount: {{ \App\PO::drrAmountBalance($shipment->poId) }}</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>DRR Qty <i class="font-red">*</i></label>
                                        <input required type="number" class="form-control text-right input-lg" name="drrQty" id="drrQty" step="0.01" value="0.00" max="{{\App\PO::DeliveryBalanceStatic($shipment->poId) }}">
                                        <span class="text-danger">Remaining DRR Qty: {{ \App\PO::DeliveryBalanceStatic($shipment->poId) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Invoice # <i class="font-red">*</i></label>
                                        <input required type="text"name="inv" class="form-control input-lg">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <div class="col-md-9">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 250px; height: 200px;"> </div>
                                                <div>
                                                    <span class="btn red btn-outline btn-file">
                                                        <span class="fileinput-new"> Select Discrepancy Attachment </span>
                                                        <span class="fileinput-exists"> Change </span>
                                                        <input type="file" name="uploadFile[]"> </span>
                                                    <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            {{--
                            @if($shipment->po_details->delivery_term != 'consignment items')
                                <h3 class="form-section">Payment Schedule</h3>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Amount <i class="font-red">*</i></label>
                                            <input required type="number" name="payment_amount" id="payment_amount" step="0.01" value="0.00" class="form-control input-lg text-right">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Payment Date <i class="font-red">*</i></label>
                                            <input required style="width: 100%;" name="payment_date" class="form-control form-control-inline date-picker input-lg" type="text" data-date-format="yyyy-mm-dd"/>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Payment Type <i class="font-red">*</i></label>
                                            <input required type="text"name="payment_type" class="form-control input-lg">
                                        </div>
                                    </div>
                                </div>
                            @endif
                            --}}
                            <h3 class="form-section"></h3>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-lg btn-primary pull-right"><i class="fa fa-save"></i> Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  
            </form>
        </div>
        <div class="col-md-2"></div>
    </div>
</div>
@endsection

@section('pagejs')
    <script src="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>

    <script>
        $(document).ready(function(){
            
            $("#drrAmount").keypress(function(e){
                var keyCode = e.which;

                if ( (keyCode != 8 || keyCode ==32 ) && (keyCode < 48 || keyCode > 57)) { 
                  return false;
                }
            });

            $("#drrQty").keypress(function(e){
                var keyCode = e.which;

                if ( (keyCode != 8 || keyCode ==32 ) && (keyCode < 48 || keyCode > 57)) { 
                  return false;
                }
            });

            $("#payment_amount").keypress(function(e){
                var keyCode = e.which;

                if ( (keyCode != 8 || keyCode ==32 ) && (keyCode < 48 || keyCode > 57)) { 
                  return false;
                }
            });
        });
    </script>
@endsection