@extends('layouts.app')

@section('pagecss')
    <link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="page-content">
    <!-- BEGIN BREADCRUMBS -->
    <div class="breadcrumbs">
        <h1>PO Status</h1>
        <ol class="breadcrumb">
            <li>
                <a href="#">Dashboard</a>
            </li>
            <li>
                <a href="#">Reports</a>
            </li>
            <li class="active">PO Status</li>
        </ol>
    </div>
    <!-- END BREADCRUMBS -->
    
    <div class="row">
        <div class="col-md-12 well">
            <form autocomplete="off" id="form" class="form-inline">
                <div class="form-group">
                    <select name="status" id="status" class="form-control">
                        <option @if(($_GET['status'] ?? '') == 'OPEN') selected @endif value="OPEN">OPEN</option>
                        <option @if(($_GET['status'] ?? '') == 'CLOSED') selected @endif value="CLOSED">CLOSED</option>
                    </select>
                </div>
                <div class="form-group">
                    <input style="width: 100%;" name="from" id="from" class="form-control form-control-inline date-picker" type="text" data-date-format="yyyy-mm-dd" data-date-end-date="+0d"/ value="{{ app('request')->input('from') }}" placeholder="From (optional)">
                </div>
                <div class="form-group">
                    <input style="width: 100%;" name="to" id="to" class="form-control form-control-inline date-picker" type="text" data-date-format="yyyy-mm-dd" data-date-end-date="+0d" value="{{ app('request')->input('to') }}" placeholder="To (optional)" />
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('po.status') }}" class="btn default">Reset</a>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN SAMPLE TABLE PORTLET-->
            <div class="portlet">

                <div class="portlet-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-advance table-hover" id="delivery_status_tbl">
                            <thead>
                                <th>#</th>
                                <th style="width: 10%;">PO Number</th>
                                <th style="width: 20%;">Supplier</th>
                                <th>Completion Date</th>
                                <th>Payment Progress</th>
                                <th>Shipment Progress</th>
                                <th>Delivery Progress</th>
                                <th>Delivery Terms</th>
                                <th>Status</th>
                            </thead>
                            <tbody>
                                @forelse($purchases as $purchase)
                                    <tr>
                                        <td>{{ ($purchases->currentpage()-1) * $purchases->perpage() + $loop->index + 1 }}</td>
                                        <td>{{ $purchase->poNumber }}</td>
                                        <td>{{ $purchase->supplier_name->name }}</td>
                                        <td>{{ $purchase->expectedCompletionDate }}</td>
                                        <td>{{ \App\PO::paymentProgress($purchase->amount,$purchase->id) }}% PAID</td>
                                        <td>{{ \App\logistics::shipment_status($purchase->id) }} SHIPPED</td>
                                        <td>{{ \App\PO::deliveryProgress($purchase->qty,$purchase->id) }}% DELIVERED</td>
                                        <td class="text-uppercase">{{ $purchase->delivery_term }}</td>
                                        <td>{{ $purchase->status }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="9"><center>No deliveries found.</center></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Start Pagination --}}
                        <div class="row">
                            <div class="col-md-6">
                                <p>Showing {{$purchases->firstItem()}} to {{$purchases->lastItem()}} of {{$purchases->total()}} items</p>
                            </div>
                            <div class="col-md-6">
                                <span class="pull-right">{{ $purchases->appends($param)->links() }}</span>
                            </div>
                        </div>
                    {{-- End Pagination --}}
                </div>
            </div>
            <!-- END SAMPLE TABLE PORTLET-->
        </div>
    </div>
</div>

<div class="modal effect-scale" id="prompt-invalid" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <form action="/delete-payment" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Error</h4>
                </div>
                <div class="modal-body"> Please provide a valid date range.
                    <input type="hidden" name="pid" id="d_pid">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>  
    </div>
</div>

@endsection

@section('pagejs')
    <script src="{{ asset('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>

    <script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/pages/scripts/components-date-time-pickers.min.js') }}" type="text/javascript"></script>
    
    <script>
        $('.date-picker').datepicker({
            orientation: 'bottom',
            autoclose: true
        });
        /*** handles the click function on filter classes, redirects it to function filter ***/
        $('#form').submit(function(e){
            e.preventDefault();

            if($('#status').val() == 'Delivered'){
                if($('#from').val() == '' || $('#to').val() == ''){
                    
                    $('#prompt-invalid').modal('show');

                    return false;
                } else {
                    filter();
                }
                
            } else {

                filter();
            }
            
        });

        /*** generate the parameters for filtering of pages ***/
        function filter(){

            var url = '';

            if(($('#status').val().length)>0)
                url += '&status='+$('#status').val();
            if(($('#from').val().length)>0)
                url += '&from='+$('#from').val();
            if(($('#to').val().length)>0)
                url += '&to='+$('#to').val();         

            url = url.substring(1, url.length);

            window.location.href = "{{ route('filter.po.status') }}?"+url;
        }

    </script>
@endsection