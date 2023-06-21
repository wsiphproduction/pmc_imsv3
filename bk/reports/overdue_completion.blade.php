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
                                    <th>PO Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($collection as $data)
                                    <tr>
                                        <td>{{ ($collection->currentpage()-1) * $collection->perpage() + $loop->index + 1 }}</td>
                                        <td><a target="_blank" href="/ims/po/details/{{ $data->id }}">{{ $data->poNumber }}</a></td>
                                        <td>{{ $data->supplier_name->name }}</td>
                                        <td>{{ $data->orderDate }}</td>
                                        <td>{{ $data->expectedCompletionDate }}</td>
                                        <td class="text-uppercase">{{ $data->delivery_term }}</td>
                                        <td>{{ $data->status }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6"><center>No Overdue delivery Found</center></td>
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