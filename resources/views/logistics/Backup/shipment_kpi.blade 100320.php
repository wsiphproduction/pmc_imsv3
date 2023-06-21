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
        <h1>Shipment KPI <small>per delivered waybill</small></h1>
        <ol class="breadcrumb">
            <li>
                <a href="/dashboard">Dashboard</a>
            </li>
            <li class="active">Shipment KPI</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-md-12">
            <form autocomplete="off" id="form" class="form-inline well">
                <div class="form-group">
                    <input style="width: 300px;" name="from" id="from" class="form-control form-control-inline date-picker" type="text" data-date-format="yyyy-mm-dd" data-date-end-date="+0d"/ value="{{ app('request')->input('from') }}" placeholder="From">
                </div>
                <div class="form-group">
                    <input style="width: 300px;" name="to" id="to" class="form-control form-control-inline date-picker" type="text" data-date-format="yyyy-mm-dd" data-date-end-date="+0d" value="{{ app('request')->input('to') }}" placeholder="To" />
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('shipment.kpi') }}" class="btn default">Reset</a>
            </form>
        </div>
    </div> 

    <div class="row">
        <div class="col-md-12">
            <div class="portlet light portlet-fit bordered">
                <div class="portlet-body">
                    <div class="table-scrollable">
                        <table class="table table-hover table-bordered table-advance">
                            <thead>
                                <tr class="uppercase">
                                    <th>#</th>
                                    <th style="width: 5%;">PO Number</th>
                                    <th style="width: 25%;">Supplier</th>
                                    <th>Waybill</th>
                                    <th><center>Shipping <br><small>time/days</small></center></th>
                                    <th><center>Clearing <br><small>time/days</small></center></th>
                                    <th><center>On-Time Ordering</center></th>
                                    <th><center>Clearing Time</center></th>
                                    <th>Origin</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($datas as $data)
                                    @php
                                        $diff = \Carbon\Carbon::parse($data->customStartDate)->diffInDays($data->customClearedDate);
                                    @endphp
                                    <tr>
                                        <td>{{ ($datas ->currentpage()-1) * $datas ->perpage() + $loop->index + 1 }}</td>
                                        <td>{{ $data->po_details->poNumber }}</td>
                                        <td class="text-uppercase">{{ $data->po_details->supplier_name->name }}</td>
                                        <td>{{ $data->waybill }}</td>
                                        <td>{{ \Carbon\Carbon::parse($data->departure_dt)->diffInDays($data->portArrivalDate) }} day(s)</td>
                                        <td>{{ \Carbon\Carbon::parse($data->customStartDate)->diffInDays($data->customClearedDate) }} day(s)</td>
                                        <td>
                                            @if($data->actualDeliveryDate <= $data->expectedDeliveryDate)
                                                <span style="font-size:10px;" class="label label-success tet">ON-TIME</span>
                                            @else
                                                <span style="font-size:10px;" class="label label-xs label-danger">DELAYED</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($diff > 10)
                                                <span style="font-size:11px;" class="label label-danger">{{ $diff }} DAYS DELAYED</span>
                                            @else
                                                <span style="font-size:11px;" class="label label-success">ON TIME</span>
                                            @endif
                                        </td>
                                        <td class="text-uppercase">{{ $data->po_details->country->country_code }}</td>
                                        <td class="text-uppercase">{{ $data->log_type }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="9"><center>No deliveries found.</center></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p>Showing {{$datas->firstItem()}} to {{$datas->lastItem()}} of {{$datas->total()}} waybills</p>
                        </div>
                        <div class="col-md-6">
                            <span class="pull-right">{{ $datas->appends($param)->links() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal effect-scale" id="prompt-no-value" tabindex="-1" role="dialog" aria-hidden="true">
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

        if($('#from').val() == '' && $('#to').val() == ''){
            
            $('#prompt-no-value').modal('show');
            
            return false;

        } else {

            filter();

        }
        
    });

    /*** generate the parameters for filtering of pages ***/
    function filter(){

        var url = '';
        url = 'from='+$('#from').val()+'&to='+$('#to').val();

        window.location.href = "{{ route('filter.shipment.kpi') }}?"+url;
    }
</script>
@endsection