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
        <div class="col-md-12 well">
            @php

                if(isset($_GET['status'])){
                    $status = app('request')->input('status');
                } else {
                    $status = 'Pending';
                }

                if(isset($_GET['from'])){
                    $from = app('request')->input('from');
                    $to = app('request')->input('to');
                } else {
                    $from = '';
                    $to = '';
                }

            @endphp

            <a href="{{ route('export.overdue-payment') }}" style="margin-right: 5px;" class="btn purple pull-right">Export</a>
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
                                    <th>Amount</th>
                                    <th>Currency</th>
                                    <th>Payment Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($collection as $data)
                                    <tr>
                                        <td>{{ ($collection->currentpage()-1) * $collection->perpage() + $loop->index + 1 }}</td>
                                        <!-- <td><a target="_blank" href="{{ route('po.details',$data->poId) }}">{{ $data->po_details->poNumber }}</a></td> -->

                                        <td><a href="{{ route('po.details',$data->poId) }}">{{ $data->po_details->poNumber }}</a></td>

                                        <td>{{ $data->po_details->supplier_name->name }}</td>
                                        <td class="text-right">{{ number_format($data->amount,2) }}</td>
                                        <td>{{ $data->po_details->currency }}</td>
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
                                <span class="pull-right">{{ $collection->links() }}</span>
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
@endsection