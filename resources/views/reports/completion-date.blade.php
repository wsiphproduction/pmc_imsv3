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
        <h1>Overdue Payables</h1>
        <ol class="breadcrumb">
            <li>
                <!-- <a href="/dashboard">Dashboard</a> -->
                <a href="{{ route('ims.dashboard') }}">Dashboard</a>
            </li>
            <li>
                <a href="#">Reports</a>
            </li>
            <li class="active">Overdue Payables</li>
        </ol>
    </div>
    <!-- END BREADCRUMBS -->

    <div class="row">
        <div class="col-md-2"></div>

        <div class="col-md-8">
            <div class="portlet light bg-inverse">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-equalizer font-red-sunglo"></i>
                        <span class="caption-subject font-red-sunglo bold uppercase">Advance Search</span>
                    </div>
                    <div class="tools">
                        <a href="" class="@if(($_GET['from'] ?? '') == '') expand @else collapse @endif"> </a>
                        <a href="#portlet-config" data-toggle="modal" class="config"> </a>
                        <a href="" class="reload"> </a>
                    </div>
                </div>
                <div class="portlet-body form @if(($_GET['from'] ?? '') == '') collapse @endif">
                    <form action="#" class="horizontal-form" id="form" autocomplete="off">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>From</label>
                                        <input style="width: 100%;" name="from" id="from" class="form-control form-control-inline date-picker" type="text" data-date-format="yyyy-mm-dd" value="{{ app('request')->input('from') }}" data-date-end-date="+0d" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>To</label>
                                        <input style="width: 100%;" name="to" id="to" class="form-control form-control-inline date-picker" type="text" data-date-format="yyyy-mm-dd" value="{{ app('request')->input('to') }}" data-date-end-date="+0d" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions right">
                            <a href="{{ route('overdue.shipments') }}" class="btn default">Reset Search</a>
                            <button type="submit" class="btn blue">
                                <i class="fa fa-search"></i> Search</button>
                        </div>
                    </form>
                    <!-- END FORM-->
                </div>
            </div>
        </div>

        <div class="col-md-2"></div>
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
                                    <th>Amount</th>
                                    <th>Payment Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($collection as $data)
                                    <tr>
                                        <td>{{ ($collection->currentpage()-1) * $collection->perpage() + $loop->index + 1 }}</td>
                                        <td>{{ $data->po_details->poNumber }}</td>
                                        <td>{{ $data->po_details->supplier_name->name }}</td>
                                        <td>{{ $data->amount }}</td>
                                        <td>{{ $data->paymentDate }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6"><center>No overdue payables found.</center></td>
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

            filter();
        });

        /*** generate the parameters for filtering of pages ***/
        function filter(){

            var url = '';
                url = 'from='+$('#from').val()+'&to='+$('#to').val();     

            window.location.href = "{{ route('filter.overdue.payables') }}?"+url;
        }

    </script>
@endsection