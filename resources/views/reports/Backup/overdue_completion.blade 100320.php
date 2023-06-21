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
        <h1>Overdue Completion Date</h1>
        <ol class="breadcrumb">
            <li>
                <a href="#">Dashboard</a>
            </li>
            <li>
                <a href="#">Reports</a>
            </li>
            <li class="active">Overdue Completion Date</li>
        </ol>
    </div>
    <!-- END BREADCRUMBS -->
    
    <div class="row">
        <div class="col-md-12 well">
            <form autocomplete="off" id="form" class="form-inline">
                <div class="form-group">
                    <input style="width: 100%;" name="from" id="from" class="form-control form-control-inline date-picker" type="text" data-date-format="yyyy-mm-dd" data-date-end-date="+0d" value="{{ app('request')->input('from') }}" placeholder="From" />
                </div>
                <div class="form-group">
                    <input style="width: 100%;" name="to" id="to" class="form-control form-control-inline date-picker" type="text" data-date-format="yyyy-mm-dd" data-date-end-date="+0d" value="{{ app('request')->input('to') }}" placeholder="To" />
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('overdue.completion') }}" class="btn default">Reset</a>

                @if(isset($_GET['from']))
                <a href="{{ route('export.overdue-po-with-daterange',[app('request')->input('from'),app('request')->input('to')]) }}" style="margin-right: 5px;" class="btn purple pull-right">Export</a>
                @else
                <a href="{{ route('export.overdue-po') }}" style="margin-right: 5px;" class="btn purple pull-right">Export</a>
                @endif
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
                                    <th>Date Ordered</th>
                                    <th>Expected Completion Date</th>
                                    <th>Delivery Term</th>
                                    <th>Shipment Progress</th>
                                    <th>Shipment Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $row = 1; @endphp
                                @forelse($collection as $data)
                                    <tr>
                                        <td>{{ $row++ }}</td>
                                        <td><a target="_blank" href="/ims/po/details/{{ $data->id }}">{{ $data->poNumber }}</a></td>
                                        <td>{{ $data->supplier_name->name }}</td>
                                        <td>{{ $data->orderDate }}</td>
                                        <td>{{ $data->expectedCompletionDate }}</td>
                                        <td class="text-uppercase">{{ $data->delivery_term }}</td>
                                        <td>{!! \App\logistics::delivery_status($data->id) !!}</td>
                                        <td>{{ \App\logistics::latest_delivery_status($data->id) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6"><center>No Overdue delivery Found</center></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
        $('.date-picker').datepicker({
            orientation: 'bottom',
            autoclose: true
        });
        /*** handles the click function on filter classes, redirects it to function filter ***/
        $('#form').submit(function(e){
            e.preventDefault();

            filter();
        });

        /*** generate the parameters for filtering of pages ***/
        function filter(){

            var url = '';
                url = 'from='+$('#from').val()+'&to='+$('#to').val();     

            window.location.href = "{{ route('filter.overdue.completion') }}?"+url;
        }

    </script>
@endsection