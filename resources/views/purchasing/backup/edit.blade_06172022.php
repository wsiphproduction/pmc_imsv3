@extends('layouts.app')

@section('pagecss')
    <link href="{{env('APP_URL')}}/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        #file_preview{
            padding: 10px;
        }

        #file_preview img{
            width: 200px;
            padding: 5px;
        }
    </style>
@endsection

@section('content')
<div class="page-content">
    <!-- BEGIN BREADCRUMBS -->
    <div class="breadcrumbs">
        <h1>Update Purchase Order</h1>
        <ol class="breadcrumb">
            <li>
                <!-- <a href="/dashboard">Dashboard</a> -->
                <a href="{{ route('ims.dashboard') }}">Dashboard</a>
            </li>
            <li>
                <a href="{{env('APP_URL')}}/ims/purchasing">Purchasing</a>
            </li>
            <li class="active">Update PO</li>
        </ol>
    </div>
    <!-- END BREADCRUMBS -->
                    
    <div class="row">
        <div class="col-md-12">

            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-equalizer font-blue-hoki"></i>
                        <span class="caption-subject font-blue-hoki bold uppercase">Purchase Order Details</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    <form autocomplete="off" action="{{ route('po.update') }}" method="POST">
                    @csrf
                        <input type="hidden" name="id" value="{{$po->id}}">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">PO Number <i class="text-danger">*</i></label>
                                        <input required type="text" id="po_no" class="form-control" name="po_no" onchange="check_duplicate(this.value);" value="{{ $po->poNumber }}" maxlength="5" minlength="5"> 
                                        <span class="help-block" style="display:none;font-size:12px;color:red;" id="poNumberRemarks"></span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">RQ Number <i class="text-danger">*</i></label>
                                        <input required type="text" class="form-control" name="rq" value="{{ $po->rq }}"> 
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">RQ Date <i class="text-danger">*</i></label>
                                        <input required style="width: 100%;" name="rq_dt" class="form-control form-control-inline datepicker" type="text" value="{{ $po->rq_date }}"/>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">MRS Number <i class="text-danger">*</i></label>
                                        <input required type="text" class="form-control" name="mrs" value="{{ $po->mrs_no }}"> 
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">PO Date <i class="text-danger">*</i></label>
                                        <input required style="width: 100%;" name="dt_order" class="form-control form-control-inline datepicker" type="text" value="{{ $po->orderDate }}"/>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Delivery Terms <i class="text-danger">*</i></label>
                                        <input readonly type="text" class="form-control text-uppercase" value="{{$po->delivery_term}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Terms <i class="text-danger">*</i></label>
                                        <input readonly type="text" class="form-control text-uppercase" value="{{$po->terms}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Inco Terms <i class="text-danger">*</i></label>
                                        <select required name="incoterms" class="form-control select2">
                                            <option @if($po->incoterms == 'EXW') selected @endif value="EXW">EXW</option>
                                            <option @if($po->incoterms == 'FCA') selected @endif value="FCA">FCA</option>
                                            <option @if($po->incoterms == 'FAS') selected @endif value="FAS">FAS</option>
                                            <option @if($po->incoterms == 'FOB') selected @endif value="FOB">FOB</option>
                                            <option @if($po->incoterms == 'CPT') selected @endif value="CPT">CPT</option>
                                            <option @if($po->incoterms == 'CFR') selected @endif value="CFR">CFR</option>
                                            <option @if($po->incoterms == 'CIF') selected @endif value="CIF">CIF</option>
                                            <option @if($po->incoterms == 'CIP') selected @endif value="CIP">CIP</option>
                                            <option @if($po->incoterms == 'DAT') selected @endif value="DAT">DAT</option>
                                            <option @if($po->incoterms == 'DAP') selected @endif value="DAP">DAP</option>
                                            <option @if($po->incoterms == 'DDP') selected @endif value="DDP">DDP</option>
                                        </select> 
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Supplier <i class="text-danger">*</i></label>
                                        <select required name="supplier" class="form-control select2">
                                            <option value=""></option>
                                            @foreach($supplier as $s)
                                            <option @if($po->supplier == $s->id) selected @endif value="{{ $s->id }}">{{ $s->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Supplier's Lead Time <i class="text-danger">*</i></label>
                                        <textarea required type="text" rows="1" name="lead_time" class="form-control">{{ $po->suppliers_lead_time }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Commodity <i class="text-danger">*</i></label>
                                        <input required type="text" class="form-control" name="item_commodity" value="{{ $po->itemCommodity }}"> 
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Destination Port <i class="text-danger">*</i></label>
                                        <input required class="form-control" name="dest_port" size="16" type="text" value="{{ $po->destination_port }}" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Expected Supplier's Delivery Date <i class="text-danger">*</i></label>
                                        <input required style="width: 100%;" name="dt_completion" class="form-control form-control-inline datepicker" type="text" value="{{ $po->expectedCompletionDate }}"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Date needed by End-User <i class="text-danger">*</i></label>
                                        <input required style="width: 100%;" name="date_needed" class="form-control form-control-inline datepicker" type="text" value="{{ $po->expectedDeliveryDate }}"/>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">PO Amount <i class="text-danger">*</i></label>
                                        <input required type="number" step="0.01" class="form-control text-right" id="po_amount" name="amount" value="{{ number_format($po->amount,2,'.','') }}"> 
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Currency <i class="text-danger">*</i></label>
                                        <select required name="currency" class="form-control select2">
                                            <option @if($po->currency == 'AUD' ? "selected" : '')@endif value="AUD">AUD</option>
                                            <option @if($po->currency == 'CAD' ? "selected" : '')@endif value="CAD">CAD</option>
                                            <option @if($po->currency == 'GBP' ? "selected" : '')@endif value="GBP">GBP</option>
                                            <option @if($po->currency == 'USD' ? "selected" : '')@endif value="USD">USD</option>
                                            <option @if($po->currency == 'EUR' ? "selected" : '')@endif value="EUR">EUR</option>
                                            <option @if($po->currency == 'SGD' ? "selected" : '')@endif value="SGD">SGD</option>
                                            <option @if($po->currency == 'PHP' ? "selected" : '')@endif value="PHP">PHP</option>
                                        </select> 
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Quantity <i class="text-danger">*</i></label>
                                        <input required type="number" step="0.01" class="form-control text-right" id="poqty" name="qty" value="{{ $po->qty }}"> 
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Origin <i class="text-danger">*</i></label>
                                        <select required name="origin" class="form-control select2">
                                            @foreach($origin as $o)
                                                <option @if($po->origin == $o->country_code) selected @endif value="{{$o->country_code}}">{{$o->country}}</option>
                                            @endforeach 
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">PO Update Recepients (Email address)</label>
                                        <input type="text" class="form-control" id="email_receivers" name="email_receivers" value="{{$po->email_receivers}}"> 
                                        <span class="help-block">
                                            Separate each email address with a comma (,)
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions right">
                            <a href="{{ route('po.list') }}" class="btn default">Cancel</a>
                            <button type="submit" class="btn blue">
                                <i class="fa fa-check"></i> Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
    <script src="{{env('APP_URL')}}/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/pages/scripts/components-select2.min.j" type="text/javascript"></script>

    <script src="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        
        $(document).on('click', '.datepicker', function(){
            $(this).datepicker({
                orientation: 'bottom',
                format: 'yyyy-mm-dd',
                autoclose: true,
            }).focus();

            $(this).removeClass('datepicker'); 
        });

        $(document).ready(function(){
            
            $('form').submit(function(){
                $(this).find('button[type=submit]').prop('disabled', true);
            });
            
            $("#po_no").keypress(function(e){
                var keyCode = e.which;

                if ( (keyCode != 8 || keyCode ==32 ) && (keyCode < 48 || keyCode > 57)) { 
                  return false;
                }
            });

        });

        function check_duplicate(po){
            $.ajax({
                type: "GET",
                url: "{{env('APP_URL')}}/ims/po/check_duplicate/"+po,
                success: function( response ) {
                    if(response != 'none'){
                        $('#poNumberRemarks').html(response);
                        $('#poNumberRemarks').show();
                    }
                }
            });
        }
    </script>
@endsection