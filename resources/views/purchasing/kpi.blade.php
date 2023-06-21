@extends('layouts.app')

@section('pagecss')
<link href="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
<link href="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />

<style>
    .poList tbody tr td  { font-size: 12px; }
</style>
@endsection

@section('content')
<div class="page-content">
    <!-- BEGIN BREADCRUMBS -->
    <div class="breadcrumbs">
        <h1>Purchasing KPI</h1>
        <ol class="breadcrumb">
            <li>
                <!-- <a href="/dashboard">Dashboard</a> -->
                <a href="{{ route('ims.dashboard') }}">Dashboard</a>
            </li>
            <li class="active">Purchasing kpi</li>
        </ol>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light portlet-fit bordered">
                <div class="portlet-body">
                    <div class="table-scrollable">
                        <table class="table table-hover table-bordered table-advance">
                            <thead>
                                <tr class="uppercase">
                                    <th style="width:5%;">#</th>
                                    <th style="width:10%;">PO Number</th>
                                    <th style="width:35%;">Supplier</th>
                                    <th style="width:10%;">RQ Date</th>
                                    <th style="width:10%;">PO Date</th>
                                    <th style="width:10%;">Expected Completion Date</th>
                                    <th style="width:10%">Date Needed by End User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($datas as $data)
                                    <tr>
                                        <td>{{ ($datas->currentpage()-1) * $datas ->perpage() + $loop->index + 1 }}</td>
                                        <td>{{ $data->poNumber }}</td>
                                        <td>{{ $data->supplier_name->name }}</td>
                                        <td>{{ $data->rq_date }}</td>
                                        <td>{{ $data->orderDate }}</td>
                                        <td>{{ $data->expectedCompletionDate }}</td>
                                        <td>{{ $data->expectedDeliveryDate }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-uppercase"><center>no po records found</center></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p>Showing {{$datas->firstItem()}} to {{$datas->lastItem()}} of {{$datas->total()}} waybills</p>
                        </div>
                        <div class="col-md-6">
                            <span class="pull-right">{{ $datas->links() }}</span>
                        </div>
                    </div>
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
@endsection