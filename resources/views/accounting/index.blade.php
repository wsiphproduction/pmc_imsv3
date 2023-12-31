@extends('layouts.app')

@section('pagecss')
    <link href="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
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

            <li class="active">Payments</li>
        </ol>
    </div>
    <!-- END BREADCRUMBS -->

    <div class="row">
        <div class="col-md-9 well">
            <form autocomplete="off" id="form" class="form-inline">
                <div class="form-group">
                    <select name="type" id="type" class="form-control">
                        <option @if(($_GET['status'] ?? '') == 2) selected @endif value="2">All</option>
                        <option @if(($_GET['status'] ?? '') == 1) selected @endif value="1">PAID</option>
                        <option @if(($_GET['status'] ?? '') == 0) selected @endif value="0">PENDING</option>
                    </select>
                </div>
                <div class="form-group">
                    <input style="width: 300px;" name="from" id="from" class="form-control form-control-inline date-picker" type="text" data-date-format="yyyy-mm-dd" data-date-start-date="+0d"/ value="{{ app('request')->input('from') }}" placeholder="From">
                </div>
                <div class="form-group">
                    <input style="width: 300px;" name="to" id="to" class="form-control form-control-inline date-picker" type="text" data-date-format="yyyy-mm-dd" data-date-start-date="+0d" value="{{ app('request')->input('to') }}" placeholder="To" />
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('accounting.payments') }}" class="btn default">Reset</a>
            </form>
        </div>
        <div class="col-md-3 well">
            <form autocomplete="off" id="search_po">
                <div class="input-group">
                    <input type="text" id="poNumber" class="form-control" placeholder="Search PO Number" value="{{ app('request')->input('poNumber') }}">
                    <span class="input-group-btn">
                        <button class="btn blue" type="submit">Search</button>
                    </span>
                </div>
            </form>
        </div>
    </div> 

    <div class="row">
        <div class="col-md-12">
            <div class="portlet light portlet-fit bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-credit-card font-red"></i>
                        <span class="caption-subject font-red sbold uppercase">Payments</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-scrollable">
                        <table class="table table-hover table-bordered table-advance">
                            <thead>
                                <tr class="uppercase">
                                    <th style="width: 3%;">#</th>
                                    <th style="width: 10%;">PO Number</th>
                                    <th style="width: 20%;">Supplier</th>
                                    <th style="width: 10%;">Payment Date</th>
                                    <th style="width: 7%;">Amount</th>
                                    <th style="width: 5%;">Currency</th>
                                    <th style="width: 15%;">Remarks</th>
                                    <th style="width: 5%;">Status</th>
                                    <th style="width: 10%;">Actual Payment Date</th>
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
                                        <td>{{ $payment->remarks }}</td>
                                        <td>{!! \App\PaymentSchedule::is_paid($payment->paymentDate) !!}</td>
                                        <td>{{ $payment->actualPaymentDate }}</td>
                                        <td>
                                            <div class="btn-toolbar margin-bottom-2">
                                                <div class="btn-group btn-group-sm btn-group-solid">
                                                    @if($edit)
                                                    <a href="{{env('APP_URL')}}/ims/po/details/{{$payment->poId}}" class="btn btn-sm purple tooltips" data-container="body" data-placement="top" data-original-title="View PO Details">                                                    
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    @else
                                                    <button disabled href="{{env('APP_URL')}}/ims/po/details/{{$payment->poId}}" class="btn btn-sm purple tooltips" data-container="body" data-placement="top" data-original-title="View PO Details">                                                    
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                    @endif
                                                    @if($payment->isPaid == 0)
                                                        @if($edit)
                                                        <a href="#paidModal" data-toggle="modal" data-amount="{{number_format($payment->amount,2)}}" data-poid="{{$payment->poId}}" data-ppid="{{ $payment->id }}" data-pon="{{ $payment->po_details->poNumber }}" class="paid btn btn-sm blue tooltips" data-container="body" data-placement="top" data-original-title="Mark Payment as Paid">
                                                            <i class="icon-check"></i>
                                                        </a>                                                        

                                                        <a href="{{env('APP_URL')}}/ims/payment-schedule/edit/{{$payment->poId}}" class="btn btn-sm btn-warning tooltips" data-container="body" data-placement="top" data-original-title="Update Payment">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        @else
                                                        <button disabled href="#paidModal" data-toggle="modal" data-amount="{{number_format($payment->amount,2)}}" data-poid="{{$payment->poId}}" data-ppid="{{ $payment->id }}" data-pon="{{ $payment->po_details->poNumber }}" class="paid btn btn-sm blue tooltips" data-container="body" data-placement="top" data-original-title="Mark Payment as Paid">
                                                            <i class="icon-check"></i>
                                                        </button>
                                                        <!-- <a href="/ims/payment-schedule/edit/{{$payment->poId}}" class="btn btn-sm btn-warning tooltips" data-container="body" data-placement="top" data-original-title="Update Payment"> -->

                                                        <button disabled href="{{env('APP_URL')}}/ims/payment-schedule/edit/{{$payment->poId}}" class="btn btn-sm btn-warning tooltips" data-container="body" data-placement="top" data-original-title="Update Payment">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        @endif
                                                        @if($delete)
                                                        <a href="#delete_payment" data-toggle="modal" data-dpid="{{$payment->id}}" class="btn btn-sm btn-danger tooltips delete" data-container="body" data-placement="top" data-original-title="Delete Payment">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                        @else
                                                        <button disabled href="#delete_payment" data-toggle="modal" data-dpid="{{$payment->id}}" class="btn btn-sm btn-danger tooltips delete" data-container="body" data-placement="top" data-original-title="Delete Payment">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-uppercase"><center>No Payments found.</center></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Start Pagination --}}
                        <div class="row">
                            <div class="col-md-6">
                                <p>Showing {{$payments->firstItem()}} to {{$payments->lastItem()}} of {{$payments->total()}} items</p>
                            </div>
                            <div class="col-md-6">
                                <span class="pull-right">{{ $payments->appends($param)->links() }}</span>
                            </div>
                        </div>
                    {{-- End Pagination --}}
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
    <script src="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>

    <script>
        $('.date-picker').datepicker({
            orientation: 'bottom',
            autoclose: true
        });
        /*** handles the click function on filter classes, redirects it to function filter ***/
        $('#form').submit(function(e){
            e.preventDefault();

            if($('#from').val() == '' || $('#to').val() == ''){
                
                $('#prompt-no-value').modal('show');
                
                return false;

            } else {

                filter();

            }
            
        });

        /*** generate the parameters for filtering of pages ***/
        function filter(){

            var url = '';
            url = 'type='+$('#type').val()+'&from='+$('#from').val()+'&to='+$('#to').val();

            window.location.href = "{{ route('filter.payments') }}?"+url;
        }

        function mark_as_paid(){
            $('#paidModal').modal('show');
        }

        $(document).on('click', '.paid', function() {

            $('#poid').val($(this).data('poid'));
            $('#pon').val($(this).data('pon'));
            $('#ppid').val($(this).data('ppid'));
            $('#amount').val($(this).data('amount'));
            
        });

        $(document).on('click','.delete',function(){
            $('#d_pid').val($(this).data('dpid'));
        });

        $('#search_po').submit(function(e){
            e.preventDefault(); 

            search();
        });

        /*** generate the parameters for filtering of pages ***/
        function search(){

            var url = '';
                url += 'poNumber='+$('#poNumber').val();


            window.location.href = "{{ route('accounting.po.search') }}?"+url;
        }

    </script>
@endsection