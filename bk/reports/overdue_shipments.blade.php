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
        <h1>Overdue Deliveries <small>per waybill</small></h1>
        <ol class="breadcrumb">
            <li>
                <a href="#">Dashboard</a>
            </li>
            <li>
                <a href="#">Reports</a>
            </li>
            <li class="active">Overdue Deliveries per waybill</li>
        </ol>
    </div>
    <!-- END BREADCRUMBS -->
    
    <div class="row">
        <div class="col-md-12 well">
            <form autocomplete="off" id="form" class="form-inline">
                <div class="form-group">
                    <select name="type" id="type" class="form-control">
                        <option @if(($_GET['type'] ?? '') == '1') selected @endif value="1">Date Range</option>
                        <option @if(($_GET['type'] ?? '') == '2') selected @endif value="2">All</option>
                    </select>
                </div>
                <div class="form-group">
                    <input @if(($_GET['type'] ?? '') == 2) disabled @endif style="width: 100%;" name="from" id="from" class="form-control form-control-inline date-picker" type="text" data-date-format="mm-dd-yyyy" value="{{ app('request')->input('from') }}" />
                </div>
                <div class="form-group">
                    <input @if(($_GET['type'] ?? '') == 2) disabled @endif style="width: 100%;" name="to" id="to" class="form-control form-control-inline date-picker" type="text" data-date-format="mm-dd-yyyy" value="{{ app('request')->input('to') }}" />
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('delivery.status.report') }}" class="btn default">Reset</a>
            </form>
        </div>
    </div>
                    
    <!-- BEGIN PAGE BASE CONTENT -->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN SAMPLE TABLE PORTLET-->
            <div class="portlet">

                <div class="portlet-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-advance table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>PO Number</th>
                                    <th>Supplier</th>
                                    <th>Waybill</th>
                                    <th>Supplier Committed Date</th>
                                    <th>Actual Delivery Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($collection as $data)
                                    <tr>
                                        <td>{{ ($collection->currentpage()-1) * $collection->perpage() + $loop->index + 1 }}</td>
                                        <td>{{ $data->po_details->poNumber }}</td>
                                        <td>{{ $data->po_details->supplier_name->name }}</td>
                                        <td>{{ $data->waybill }}</td>
                                        <td>{{ $data->expectedDeliveryDate }}</td>
                                        <td>{{ $data->actualDeliveryDate }}</td>
                                        <td>{{ $data->status }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-uppercase"><center>No overdue deliveries found for this month.</center></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Start Pagination --}}
                        <div class="row">
                            <div class="col-md-6">
                                <p>Showing {{$collection->firstItem()}} to {{$collection->lastItem()}} of {{$collection->total()}} items</p>
                            </div>
                            <div class="col-md-6">
                                <span class="pull-right">{{ $collection->appends($param)->links() }}</span>
                            </div>
                        </div>
                    {{-- End Pagination --}}
                </div>
            </div>
            <!-- END SAMPLE TABLE PORTLET-->
        </div>
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
        $('#type').change(function(e){
            e.preventDefault();

            if($(this).val() == '1'){
                $('#from').prop("disabled", false); 
                $('#to').prop("disabled", false);
            } else {
                $('#from').prop("disabled", true); 
                $('#to').prop("disabled", true);

                $('#from').val('');
                $('#to').val('');
            }
        });

        $('.date-picker').datepicker({
            orientation: 'bottom',
            autoclose: true,
        });
        /*** handles the click function on filter classes, redirects it to function filter ***/
        $('#form').submit(function(e){
            e.preventDefault();

            filter();
        });

        /*** generate the parameters for filtering of pages ***/
        function filter(){

            var url = '';

            if(($('#from').val().length)>0)
                url += '&from='+$('#from').val();
            if(($('#to').val().length)>0)
                url += '&to='+$('#to').val();
            if(($('#type').val().length)>0)
                url += '&type='+$('#type').val();

                url = url.substring(1, url.length);

                //url = 'type='+$('#type').val()+'&from='+$('#from').val()+'&to='+$('#to').val();     

            window.location.href = "{{ route('filter.overdue.shipments') }}?"+url;
        }

    </script>
@endsection