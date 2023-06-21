@extends('layouts.app')

@section('pagecss')
    <link href="{{env('APP_URL')}}/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="page-content">
    <div class="breadcrumbs">
        <h1><i class="fa fa-money"></i> Update Payment Schedule</h1>
        <ol class="breadcrumb">
            <li>
                <!-- <a href="/dashboard">Dashboard</a> -->
                <a href="{{ route('ims.dashboard') }}">Dashboard</a>
            </li>
            <li>
                <a href="{{env('APP_URL')}}/ims/logistics">Logistics</a>
            </li>
            <li class="active">Update Payment Schedule</li>
        </ol>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <h4>PO Amount : {{ number_format($po->amount,2) }}</h4>
            <div class="portlet light portlet-fit bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-calendar font-red"></i>
                        <span class="caption-subject font-red bold uppercase">Estimated Payment Schedule</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-scrollable">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th> Percentage </th>
                                    <th> Amount </th>
                                    <th> Payment Date </th>
                                    <th> Payment Type </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($estimated_payments as $e)
                                    <tr>
                                        <td class="text-right">{{ ($e->amount/$po->amount)*100 }}%</td>
                                        <td class="text-right"> {{ $e->amount }} </td>
                                        <td> {{ $e->paymentDate }} </td>
                                        <td class="text-uppercase"> {{ $e->payment_type }} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="portlet light portlet-fit bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-calendar font-red"></i>
                        <span class="caption-subject font-red bold uppercase">Actual Payment Schedule</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <div class="table-responsive">  
                            <form autocomplete="off" method="post" action="{{ route('payment.schedule.update') }}" id="actual_payment">
                                @csrf
                                <table class="table table-bordered" id="dynamic_field">
                                    <thead>
                                        <th>Percentage</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Payment Type</th>
                                        <th></th>
                                    </thead>
                                    <tbody>
                                        <input type="hidden" value="{{ number_format($po->amount,2,'.','') }}" id="po_amount">
                                        <input type="hidden" value="{{ $po->id }}" name="poId">

                                        @foreach($payments as $payment)
                                            @if(Auth::user()->domainAccount == 'dtablante')
                                                <tr>
                                                    <td style="display: none;"><input type="text" name="payment_id[]" value="{{$payment->id}}"></td>
                                                    <td>
                                                        <input type="number" required step="0.01" class="form-control input-lg text-right percentage" onchange="percentage_check(1);" name="percentage[]" id="percentage1" value="{{ number_format(($payment->amount/$po->amount)*100,2) }}">
                                                    </td>
                                                    <td>
                                                        <input step="0.01" onchange="amount_check(1);" type="number" class="form-control input-lg text-right input_amount" name="amount[]" id="amount1" value="{{number_format($payment->amount,2,'.','')}}">
                                                    </td>  
                                                    <td>
                                                        <input style="width: 100%;" name="date[]" class="form-control form-control-inline input-lg datepicker" type="text" value="{{ date('Y-m-d',strtotime($payment->paymentDate)) }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="payment_type[]" class="form-control input-lg text-uppercase" value="{{ $payment->payment_type }}">
                                                    </td>
                                                    <td>
                                                        <a href="#delete_payment" data-toggle="modal" data-payment_id="{{$payment->id}}" class="btn btn-danger delete_payment"><i class="fa fa-trash"></i></a>
                                                         @if($loop->last)
                                                            <button type="button" name="add" id="add" class="btn btn-success"><i class="fa fa-plus"></i></button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @else
                                                @if($payment->isPaid == 1)
                                                    <tr>
                                                        <td>
                                                            <input readonly type="text" class="form-control input-lg text-right" value="{{ number_format(($payment->amount/$po->amount)*100,2) }}">
                                                        </td>
                                                        <td>
                                                            <input readonly type="number" class="form-control input-lg text-right input_amount" value="{{number_format($payment->amount,2,'.','')}}">
                                                        </td>  
                                                        <td>
                                                            <input readonly class="form-control input-lg" type="text" value="{{ date('Y-m-d',strtotime($payment->paymentDate)) }}">
                                                        </td>
                                                        <td>
                                                            <input readonly class="form-control input-lg text-uppercase" type="text" value="{{ $payment->payment_type }}">
                                                        </td>
                                                        <td>
                                                            @if($loop->last)
                                                                <button type="button" name="add" id="add" class="btn btn-success"><i class="fa fa-plus"></i></button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td style="display: none;"><input type="text" name="payment_id[]" value="{{$payment->id}}"></td>
                                                        <td>
                                                            <input type="number" required step="0.01" class="form-control input-lg text-right percentage" onchange="percentage_check(1);" name="percentage[]" id="percentage1" value="{{ number_format(($payment->amount/$po->amount)*100,2) }}">
                                                        </td>
                                                        <td>
                                                            <input step="0.01" onchange="amount_check(1);" type="number" class="form-control input-lg text-right input_amount" name="amount[]" id="amount1" value="{{number_format($payment->amount,2,'.','')}}">
                                                        </td>  
                                                        <td>
                                                            <input style="width: 100%;" name="date[]" class="form-control form-control-inline input-lg datepicker" type="text" value="{{ date('Y-m-d',strtotime($payment->paymentDate)) }}">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="payment_type[]" class="form-control input-lg text-uppercase" value="{{ $payment->payment_type }}">
                                                        </td>
                                                        <td>
                                                            <a href="#delete_payment" data-toggle="modal" data-payment_id="{{$payment->id}}" class="btn btn-danger delete_payment"><i class="fa fa-trash"></i></a>
                                                             @if($loop->last)
                                                                <button type="button" name="add" id="add" class="btn btn-success"><i class="fa fa-plus"></i></button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endif

                                        @endforeach
                                    </tbody>
                                </table>
                                <button type="submit" class="btn btn-primary pull-right" id="btnSubmit"><i class="fa fa-check"></i> Save Changes</button>
                                <a href="{{ route('accounting.payments') }}" class="btn btn-default pull-right" style="margin-right: 5px;"><i class="fa fa-backward"></i> Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('modal.payments')
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
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>  
    </div>
</div>
@endsection

@section('pagejs')
    <script src="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>

    <script>

        // $('#actual_payment').submit(function(){
        //     var total_pecentage = 0;
        //     $(".percentage").each(function() {
        //         if(!isNaN(this.value) && this.value.length!=0) {
        //             total_pecentage += parseFloat(this.value);
        //         }
        //     });

        //     if(total_pecentage == 100){
        //         $('#btnSubmit').attr('disabled','disabled');
        //     } else {
        //         $('#prompt-invalid').modal('show');
        //         $('#span').html('Payment percentage must reach 100 %');
        //         return false;
        //     }

        // });

        $(document).on('click', '.delete_payment', function(){
            $('#d_pid').val($(this).data('payment_id'));
        });

        $(document).on('click', '.datepicker', function(){
            $(this).datepicker({
                orientation: 'bottom',
                format: 'yyyy-mm-dd',
                autoclose: true,
                // startDate: '+0d'
            }).focus();

            $(this).removeClass('datepicker'); 
        });

        $(document).ready(function(){

           var i=1;  

            $('#add').click(function(){  
               i++;  
                $('#dynamic_field').append('<tr id="row'+i+'" class="dynamic-added">'+
                    '<td style="display: none;"><input type="text" name="payment_id[]" value="new"></td>'+
                    '<td><input type="number" required step="0.01" class="form-control input-lg text-right percentage" onchange="percentage_check('+i+');" name="percentage[]" id="percentage'+i+'"></td>'+
                    '<td><input step="0.01" onchange="amount_check('+i+');" type="number" class="form-control input-lg text-right input_amount" name="amount[]" id="amount'+i+'"></td>'+
                    '<td><input style="width: 100%;" name="date[]" class="form-control form-control-inline input-lg datepicker" type="text"></td>'+
                    '<td><input name="payment_type[]" type="text" class="form-control input-lg text-uppercase"></td>'+
                    '<td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td>'+
                    '</tr>'); 
            });

            $(document).on('click', '.btn_remove', function(){  
               var button_id = $(this).attr("id");   
               $('#row'+button_id+'').remove();  
            });

        });

        function percentage_check(id)
        {
            var amount = (parseFloat($('#percentage'+id).val())/100) * parseFloat($('#po_amount').val());
            $('#amount'+id).val(amount.toFixed(2));

            var result = validateAmount();

            if(result == false){
                $('#prompt-invalid').modal('show');
                $('#percentage'+id).val('');
                $('#span').html('Your total scheduled payments exceed to '+ parseFloat($('#po_amount').val())+'. Please check!');

                $('#amount'+id).val('');
                
                return false;
            }
        }

        function amount_check(id){
            var percent = (parseFloat($('#amount'+id).val()) / parseFloat($('#po_amount').val())) * 100;

            console.log(percent);
            $('#percentage'+id).val(percent.toFixed(2));

            var result = validateAmount();

            if(result == false){
                $('#prompt-invalid').modal('show');
                $('#percentage'+id).val('');
                $('#span').html('Your total scheduled payments exceed to '+ parseFloat($('#po_amount').val())+'. Please check!');

                $('#amount'+id).val('');
                
                return false;
            }
        }

        function validateAmount()
        {
            var sum = 0;
            var po_amount = parseFloat($('#po_amount').val());
            
            $(".input_amount").each(function() {
                if(!isNaN(this.value) && this.value.length!=0) {
                    sum += parseFloat(this.value);
                }
            });

            if(sum > po_amount){
                return false;
            }
            else{
                return true;
            }
            
        }

    </script>
@endsection