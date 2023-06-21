@extends('layouts.app')

@section('pagecss')
    <link href="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />  
@endsection

@section('content')
<div class="page-content">
    <!-- BEGIN BREADCRUMBS -->
    <div class="breadcrumbs">
        <ol class="breadcrumb">
            <li>
                <!-- <a href="/dashboard">Dashboard</a> -->
                <a href="{{ route('ims.dashboard') }}">Dashboard</a>
            </li>

            <li class="active">Overdue Payables</li>
        </ol>
    </div>
    <!-- END BREADCRUMBS -->
                    
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light portlet-fit bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-credit-card font-red"></i>
                        <span class="caption-subject font-red sbold uppercase">Overdue Payables</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-scrollable">
                        <table class="table table-hover table-bordered table-advance">
                            <thead>
                                <tr class="uppercase">
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 10%;">PO Number</th>
                                    <th style="width: 30%;">Supplier</th>
                                    <th style="width: 10%;">Payment Date</th>
                                    <th style="width: 10%;">Amount</th>
                                    <th style="width: 10%;">Currency</th>
                                    <th style="width: 10%;">Aging</th>
                                    <th style="width: 15%;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                    <tr>
                                        <td>{{ ($payments->currentpage()-1) * $payments->perpage() + $loop->index + 1 }}</td>
                                        <td>{{ $payment->po_details->poNumber }}</td>
                                        <td>{{ $payment->po_details->supplier_name->name }}</td>
                                        <td>{{ $payment->paymentDate }}</td>
                                        <td class="text-right">{{ number_format($payment->amount,2) }}</td>
                                        <td>{{ $payment->po_details->currency }}</td>
                                        <td>{{ \App\PaymentSchedule::paymnt_aging($payment->paymentDate) }}</td>
                                        <td>
                                            @if($edit)
                                            <a href="#paidModal" data-toggle="modal" data-amount="{{number_format($payment->amount,2)}}" data-poid="{{$payment->poId}}" data-ppid="{{ $payment->id }}" data-pon="{{ $payment->po_details->poNumber }}" class="paid btn btn-sm blue">Mark as Paid</a>
                                            @else
                                            <button disabled href="#paidModal" data-toggle="modal" data-amount="{{number_format($payment->amount,2)}}" data-poid="{{$payment->poId}}" data-ppid="{{ $payment->id }}" data-pon="{{ $payment->po_details->poNumber }}" class="paid btn btn-sm blue">Mark as Paid</button>
                                            @endif
                                            @if($delete)
                                            <a href="javascript:;" onclick="delete_payment('{{$payment->id}}');" class="btn btn-sm btn-danger">Delete</a>
                                            @else
                                            <button disabled href="javascript:;" onclick="delete_payment('{{$payment->id}}');" class="btn btn-sm btn-danger">Delete</button>
                                           @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-uppercase"><center>No Payments for this month</center></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p>Showing {{$payments->firstItem()}} to {{$payments->lastItem()}} of {{$payments->total()}} items</p>
                        </div>
                        <div class="col-md-6">
                            <span class="pull-right">{{ $payments->links() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('modal.payments')
@endsection

@section('pagejs')
    <script src="{{env('APP_URL')}}/assets/global/scripts/datatable.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>

    <script type="text/javascript">
        
        $(document).on('click', '.paid', function() {

            $('#poid').val($(this).data('poid'));
            $('#pon').val($(this).data('pon'));
            $('#ppid').val($(this).data('ppid'));
            $('#amount').val($(this).data('amount'));
            
        });

        function delete_payment(id)
        {
            $('#delete_payment').modal('show');
            $('#d_pid').val(id);
        }

        $(document).on('click','.delete',function(){
            $('#d_pid').val($(this).data('dpid'));
        });
    </script>
@endsection