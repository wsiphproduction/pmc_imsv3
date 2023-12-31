@extends('layouts.app')

@section('pagecss')
    <link href="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="page-content">
    <!-- BEGIN BREADCRUMBS -->
    <div class="breadcrumbs">
        <h1>Delivery Status <small>per waybill</small></h1>
        <ol class="breadcrumb">
            <li>
                <!-- <a href="/dashboard">Dashboard</a> -->
                <a href="{{ route('ims.dashboard') }}">Dashboard</a>
            </li>
            <li>
                <a href="#">Reports</a>
            </li>
            <li class="active">Delivery Status</li>
        </ol>
    </div>
    <!-- END BREADCRUMBS -->
    
    <div class="row">
        <div class="col-md-12 well">
            <form autocomplete="off" id="form" class="form-inline">
                <div class="form-group">
                    <select name="status" id="status" class="form-control">
                        <option @if(($_GET['status'] ?? '') == 'Pending') selected @endif value="Pending">Pending</option>
                        <option @if(($_GET['status'] ?? '') == 'In-Transit') selected @endif value="In-Transit">In-Transit</option>
                        <option @if(($_GET['status'] ?? '') == 'Custom Clearing') selected @endif value="Custom Clearing">Custom Clearing</option>
                        <option @if(($_GET['status'] ?? '') == 'For Pick-Up') selected @endif value="For Pick-Up">For Pick-Up</option>
                        <option @if(($_GET['status'] ?? '') == 'Delivered') selected @endif value="Delivered">Delivered</option>
                        <option @if(($_GET['status'] ?? '') == 'All') selected @endif value="All">All</option>
                    </select>
                </div>
                <div class="form-group">
                    <input @if(($_GET['status'] ?? '') == 'Delivered'  || ($_GET['status'] ?? '') == 'All' ) @else disabled @endif style="width: 100%;" name="from" id="from" class="form-control form-control-inline date-picker" type="text" data-date-format="yyyy-mm-dd" data-date-end-date="+0d"/ value="{{ app('request')->input('from') }}" placeholder="Date From">
                </div>
                <div class="form-group">
                    <input @if(($_GET['status'] ?? '') == 'Delivered'  || ($_GET['status'] ?? '') == 'All' ) @else disabled @endif style="width: 100%;" name="to" id="to" class="form-control form-control-inline date-picker" type="text" data-date-format="yyyy-mm-dd" value="{{ app('request')->input('to') }}" placeholder="Date To"/>
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('delivery.status.report') }}" class="btn default">Reset</a>
                @php

                    if(isset($_GET['status'])){
                        $status = app('request')->input('status');
                    } else {
                        $status = 'Pending';
                    }

                @endphp

                @if(isset($_GET['from']))
                <a href="{{ route('export.delivery-status-with-daterange',[app('request')->input('status'),app('request')->input('from'),app('request')->input('to')]) }}" style="margin-right: 5px;" class="btn purple pull-right">Export</a>
                @elseif(isset($_GET['status']))
                <a href="{{ route('export.delivery-status',[$status]) }}" style="margin-right: 5px;" class="btn purple pull-right">Export</a>
                @else
                <a href="{{ route('export.deliveries') }}" style="margin-right: 5px;" class="btn purple pull-right">Export</a>
                @endif
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
                                <th width="3%">#</th>
                                <th width="6%">PO Number</th>
                                <th width="25%">Supplier</th>
                                <th>Waybill</th>
                                <th>Type</th>
                                <th class="text-center">Supplier<br>Committed Date</th>

                                @if(($_GET['status'] ?? '') == 'All' || ($_GET['status'] ?? '') == 'Delivered')
                                    <th class="text-center">Actual<br>Manufactured<br>Date</th>
                                    <th>Departure Date</th>
                                    <th class="text-center">Port<br>Arrival Date</th>
                                    <th class="text-center">Custom<br>Start Date</th>
                                    <th class="text-center">Custom<br>Cleared Date</th>
                                    <th>Date Delivered</th>
                                @endif

                                @if(($_GET['status'] ?? '') == 'In-Transit')
                                    <th class="text-center">Actual<br>Manufactured<br>Date</th>
                                    <th>Departure Date</th>
                                    <th>Date Arrived</th>
                                @endif

                                @if(($_GET['status'] ?? '') == 'Custom Clearing' || ($_GET['status'] ?? '') == 'For Pick-Up')
                                    <th class="text-center">Custom<br>Start Date</th>
                                    <th class="text-center">Custom<br>Cleared Date</th>
                                @endif

                                <th>Status</th>
                            </thead>
                            <tbody>
                                @php $row = 1; @endphp

                                @forelse($shipments as $shipment)
                                    <tr>
                                        <td>{{ $row++ }}</td>                                        

                                        <td><a href="{{env('APP_URL')}}/ims/po/details/{{$shipment->po_details->id}}">{{ $shipment->po_details->poNumber }}</a></td>

                                        <td>{{ $shipment->po_details->supplier_name->name }}</td>
                                        <td>{{ $shipment->waybill == 'shipment' ? 'N/A' : $shipment->waybill }}</td>
                                        <td class="text-uppercase">{{ $shipment->log_type }}</td>
                                        <td>{{ $shipment->expectedDeliveryDate }}</td>
                                        
                                        @if(($_GET['status'] ?? '') == 'All' || ($_GET['status'] ?? '') == 'Delivered')
                                            <td>{{ $shipment->actualManufacturingDate }}</td>
                                            <td>{{ $shipment->departure_dt }}</td>
                                            <td>{{ $shipment->portArrivalDate }}</td>
                                            <td>{{ $shipment->customStartDate }}</td>
                                            <td>{{ $shipment->customClearedDate }}</td>
                                            <td>{{ $shipment->actualDeliveryDate }}</td>
                                        @endif

                                        @if(($_GET['status'] ?? '') == 'In-Transit')
                                            <td>{{ $shipment->actualManufacturingDate }}</td>
                                            <td>{{ $shipment->departure_dt }}</td>
                                            <td>{{ $shipment->portArrivalDate == NULL ? 'N/A' : $shipment->portArrivalDate }}</td>
                                        @endif
                                        
                                        @if(($_GET['status'] ?? '') == 'Custom Clearing' || ($_GET['status'] ?? '') == 'For Pick-Up')
                                            <td>{{ $shipment->customStartDate }}</td>
                                            <td>{{ $shipment->customClearedDate == NULL ? 'N/A' : $shipment->customClearedDate }}</td>
                                        @endif

                                        <td class="text-uppercase">{{ $shipment->status }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="9"><center>No deliveries found.</center></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $shipments->appends($param)->links() }}
                    </div>
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
    <script src="{{env('APP_URL')}}/assets/global/scripts/datatable.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>

    <script src="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
    
    <script>
        $('.date-picker').datepicker({
            orientation: 'bottom',
            autoclose: true
        });
        /*** handles the click function on filter classes, redirects it to function filter ***/
        $('#status').change(function(e){
            e.preventDefault();

            if($(this).val() == 'Delivered' || $(this).val() == 'All'){
                $('#from').prop("disabled", false); 
                $('#to').prop("disabled", false);
            } else {
                $('#from').prop("disabled", true); 
                $('#to').prop("disabled", true);

                $('#from').val('');
                $('#to').val('');
            }
        });

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

            window.location.href = "{{ route('filter.delivery.status') }}?"+url;
        }

    </script>
@endsection