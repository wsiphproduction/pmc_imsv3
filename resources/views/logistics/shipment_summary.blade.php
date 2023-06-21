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
        <h1>Shipment Summary <small>per delivered waybill</small></h1>
        <ol class="breadcrumb">
            <li>
                <!-- <a href="/dashboard">Dashboard</a> -->
                <a href="{{ route('ims.dashboard') }}">Dashboard</a>
            </li>
            <li class="active">Shipment Summary</li>
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
                <a href="{{ route('shipment.summary') }}" class="btn default">Reset</a>
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
                                    <th>Waybill</th>
                                    <th>Order Date</th>
                                    <th class="text-center">Estimated<br>Completion Date</th>
                                    <th class="text-center">Supplier<br>Committed Date</th>
                                    <th class="text-center">Actual<br>Date Manufactured</th>
                                    <th>Departure Date</th>
                                    <th class="text-center">Port<br>Arrival Date</th>
                                    <th class="text-center">Custom<br>Start Date</th>
                                    <th class="text-center">Custom<br>Cleared Date</th>
                                    <th>Date Delivered</th>
                                    <th>Type</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($datas as $data)
                                    <tr>
                                        <td><b class="text-danger">{{ $data->waybill }}</b><br><small>{{ $data->po_details->poNumber }} - {{ $data->po_details->supplier_name->name }}</small><br><small>{{ $data->po_details->country->country_code }}</small></td>
                                        <td>{{ $data->po_details->orderDate }}</td>
                                        <td>{{ $data->po_details->expectedCompletionDate }}</td>
                                        <td>{{ $data->expectedDeliveryDate }}</td>
                                        <td>{{ $data->actualManufacturingDate }}</td>
                                        <td>{{ $data->departure_dt }}</td>
                                        <td>{{ $data->portArrivalDate }}</td>
                                        <td>{{ $data->customStartDate }}</td>
                                        <td>{{ $data->customClearedDate }}</td>
                                        <td>{{ $data->actualDeliveryDate }}</td>
                                        <td class="text-uppercase">{{ $data->log_type }}</td>
                                        <td class="text-uppercase">{{ \App\logistics::remarks($data->logistics_id) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="12"><center>NO DELIVERED WAYBILL FOUND.</center></td></tr>
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

            window.location.href = "{{ route('filter.shipment.summary') }}?"+url;
        }
    </script>
@endsection