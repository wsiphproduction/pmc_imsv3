@extends('layouts.app')

@section('pagecss')
    <link href="{{env('APP_URL')}}/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="page-content">
    <!-- BEGIN BREADCRUMBS -->
    <div class="breadcrumbs">
        <h1>Create Purchase Order</h1>
        <ol class="breadcrumb">
            <li>
                <!-- <a href="/dashboard">Dashboard</a> -->
                <a href="{{ route('ims.dashboard') }}">Dashboard</a>
            </li>
            <li>
                <a href="{{env('APP_URL')}}/ims/purchasing">Purchasing</a>
            </li>
            <li class="active">Create PO</li>
        </ol>
    </div>
    <!-- END BREADCRUMBS -->
                    
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    <form autocomplete="off" action="{{ route('po.store') }}" method="POST" id="formid" enctype="multipart/form-data">
                    @csrf
                        <div class="form-body">
                            <h3 class="form-section">PO Details</h3>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">PO Number <i class="text-danger">*</i></label>
                                        <input required type="text" id="po_no" class="form-control" name="po_no" maxlength="5" minlength="5" onchange="check_duplicate(this.value);"> 
                                        <span class="help-block" style="display:none;font-size:12px;color:red;" id="poNumberRemarks"></span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">RQ Number <i class="text-danger">*</i></label>
                                        <input required type="text" class="form-control" name="rq">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">RQ Date <i class="text-danger">*</i></label>
                                        <input required style="width: 100%;" name="rq_date" class="form-control form-control-inline date-picker" type="text" data-date-format="yyyy-mm-dd" data-date-end-date="+0d"/>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">MRS Number</label>
                                        <input type="text" class="form-control" name="mrs_no">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">PO Date <i class="text-danger">*</i></label>
                                        <input required style="width: 100%;" name="dt_order" class="form-control form-control-inline date-picker" type="text" data-date-format="yyyy-mm-dd" data-date-end-date="+0d"/>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Delivery Terms <i class="text-danger">*</i></label>
                                        <select required name="delivery_term" class="form-control select2" onchange="payment_sched_validate($(this).val());">
                                            <option value=""> Choose Delivery Term</option>
                                            <option value="blanket order">Blanket Order</option>
                                            <option value="consignment items">Consignment Items</option>
                                            <option value="one time shipment">One Time Shipment</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Payment Terms <i class="text-danger">*</i></label>
                                        <select required name="terms" class="form-control payment_options">
                                            <option value=""> Choose Payment Terms</option>
                                            <option value="advances">Advances</option>
                                            <option value="credit">Credit</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Inco Terms <i class="text-danger">*</i></label>
                                        <select required name="incoterms" class="form-control select2">
                                            <option value=""></option>
                                            <option value="EXW">EXW (Ex Works)</option>
                                            <option value="FCA">FCA (Free Carrier)</option>
                                            <option value="FAS">FAS (Free Alongside Ship)</option>
                                            <option value="FOB">FOB (Free On Board)</option>
                                            <option value="CPT">CPT (Carriage Paid To)</option>
                                            <option value="CFR">CFR (Cost and Freight)</option>
                                            <option value="CIF">CIF (Cost Insurance & Freight)</option>
                                            <option value="CIP">CIP (Carriage & Insurance Paid To)</option>
                                            <option value="DAT">DAT (Delivered at Terminal)</option>
                                            <option value="DAP">DAP (Delivered at Place)</option>
                                            <option value="DDP">DDP (Delivered Duty Paid)</option>
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
                                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Supplier's Lead Time <i class="text-danger">*</i></label>
                                        <textarea required type="text" rows="1" name="lead_time" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Item Commodity <i class="text-danger">*</i></label>
                                        <input required type="text" class="form-control" name="item_commodity"> 
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Destination Port <i class="text-danger">*</i></label>
                                        <input required class="form-control" name="dest_port" size="16" type="text"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Expected Supplier's Delivery Date <i class="text-danger">*</i></label>
                                        <input required style="width: 100%;" name="dt_completion" class="form-control form-control-inline datepicker" type="text"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Date needed by End-User <i class="text-danger">*</i></label>
                                        <input required style="width: 100%;" name="date_needed" class="form-control form-control-inline datepicker" type="text"/>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group" id="amount_form_group">
                                        <label class="control-label">PO Amount <i class="text-danger">*</i></label>
                                        <input required type="number" step="0.01" value="0.00" class="form-control text-right" id="po_amount" name="po_amount"> 
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Currency <i class="text-danger">*</i></label>
                                        <select required name="currency" class="form-control select2">
                                            <option value=""></option>
                                            <option value="AUD">AUD</option>
                                            <option value="CAD">CAD</option>
                                            <option value="GBP">GBP</option>
                                            <option value="USD">USD</option>
                                            <option value="EUR">EUR</option>
                                            <option value="SGD">SGD</option>
                                            <option value="PHP">PHP</option>
                                        </select> 
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Quantity <i class="text-danger">*</i></label>
                                        <input required type="number" step="0.01" value="0.00" class="form-control text-right" id="poqty" name="po_qty"> 
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Origin <i class="text-danger">*</i></label>
                                        <select required name="origin" class="form-control select2">
                                            <option value=""></option>
                                            @foreach($origin as $o)
                                                <option value="{{$o->country_code}}">{{$o->country}}</option>
                                            @endforeach 
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">PO Update Recepients (Email address)</label>
                                        <input type="text" class="form-control" id="email_receivers" name="email_receivers"> 
                                        <span class="help-block">
                                            Separate each email address with a comma (,)
                                        </span>
                                    </div>
                                </div>
                            </div>


                            <h3 class="form-section">Payment Schedule &amp; Files</h3>
                            <div class="row">
                                <div class="col-md-8" id="payment_schedule">
                                    <div class="table-responsive">  
                                        <table class="table table-bordered" id="dynamic_field">
                                            <thead>
                                                <th>Percentage</th>
                                                <th>Amount</th>
                                                <th>Date</th>
                                                <th>Payment Type</th>
                                                <th></th>
                                            </thead>  
                                            <tr>
                                                <td><input required type="number" step="0.01" class="form-control text-right" onchange="percentage_check(1);" name="percentage[]" id="percentage1" placeholder="Percentage"></td>
                                                <td><input type="number" readonly class="form-control text-right a_amount" name="amount[]" id="amount1"></td>  
                                                <td>
                                                    <input required type="text" style="width: 100%;" name="date[]" id="pdate" class="form-control datepicker"/>
                                                </td>
                                                <td><input required type="text" name="payment_type[]" class="form-control" id="payment_type"></td>
                                                <td><button type="button" name="add" id="add" class="btn btn-success"><i class="fa fa-plus"></i></button></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions right">
                            <a href="{{ route('po.list') }}" class="btn default">Cancel</a>
                            @if($create)
                            <button type="submit" class="btn blue">
                                <i class="fa fa-check"></i> Save</button>
                            @else
                            <button disabled type="submit" class="btn blue">
                                <i class="fa fa-check"></i> Save</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="prompt-invalid" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Error</h4>
            </div>
            <div class="modal-body"> 
                <span id="span"></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Cancel</button>
            </div>
        </div>  
    </div>
</div>
@endsection

@section('pagejs')
    <script src="{{env('APP_URL')}}/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
    
    <script type="text/javascript">
        
        function payment_sched_validate(val){
            $(".payment_options").val('').trigger('change');

            if(val == 'consignment items'){
                $('#payment_schedule').hide();
                $('#pdate').prop('required',false);
                $('#percentage1').prop('required',false);
                $('#payment_type').prop('required',false); 

                $(".payment_options option[value='advances']").prop('disabled','disabled');
            } else {
                $(".payment_options option[value='advances']").prop('disabled','');

                $('#payment_schedule').show();
                $('#pdate').prop('required',true);
                $('#percentage1').prop('required',true);
                $('#payment_type').prop('required',true); 
            }
        }

        $(document).on('change','.payment_options',function(){
            if($(this).val() == 'credit'){
                $('#payment_schedule').hide();
                $('#pdate').prop('required',false);
                $('#percentage1').prop('required',false);
                $('#payment_type').prop('required',false); 
            } else {
                $('#payment_schedule').show();
                $('#pdate').prop('required',true);
                $('#percentage1').prop('required',true);
                $('#payment_type').prop('required',true); 
            }
        });

        $(document).on('click', '.datepicker', function(){
            $(this).datepicker({
                orientation: 'bottom',
                format: 'yyyy-mm-dd',
                autoclose: true,
                startDate: '+0d'
            }).focus();

            $(this).removeClass('datepicker'); 
        });

        $('.date-picker').datepicker({
            orientation: 'bottom',
            autoclose: true
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


            var i=1;  

            $('#add').click(function(){  
               i++;  
                $('#dynamic_field').append('<tr id="row'+i+'" class="dynamic-added"><td><input type="number" required step="0.01" class="form-control text-right" onchange="percentage_check('+i+');" name="percentage[]" id="percentage'+i+'" placeholder="Percentage"></td><td><input type="number" readonly class="form-control text-right a_amount" id="amount'+i+'" name="amount[]"/></td><td><input required style="width: 100%;" name="date[]" class="form-control form-control-inline datepicker" type="text"/></td><td><input required type="text" name="payment_type[]" class="form-control"></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>'); 
            });

            $(document).on('click', '.btn_remove', function(){  
               var button_id = $(this).attr("id");   
               $('#row'+button_id+'').remove();  
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

        function validate_payments(){
            var total_amount = 0;
            var po_amount = parseFloat($('#po_amount').val());
            
            $('.a_amount').each(function() {
                total_amount += parseFloat(this.value);
            });

            if(total_amount > po_amount){
                return false;
            }
            else{
                return true;
            }
        }

        function percentage_check(id){
            if(!parseFloat($('#po_amount').val())){
                $('#prompt-invalid').modal('show');
                $('#span').html('Please Input PO Amount first!');
                $('#amount_form_group').addClass('has-error');

                $('#percentage'+id).val('');

                return false;
            } else {
                var amount = (parseFloat($('#percentage'+id).val())/100) * parseFloat($('#po_amount').val());
                $('#amount'+id).val(amount);
            }
           
            if(!validate_payments()){
                $('#prompt-invalid').modal('show');
                $('#percentage'+id).val('');
                $('#span').html('Your total scheduled payments exceed to '+ parseFloat($('#po_amount').val())+'. Please check!');

                $('#amount'+id).val('');
                return false;
            } else {
                var amount = (parseFloat($('#percentage'+id).val())/100) * parseFloat($('#po_amount').val());
                $('#amount'+id).val(amount);
            }  
        }

</script>
@endsection