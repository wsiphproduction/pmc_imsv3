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
        <h1>DRR Summary</h1>
        <ol class="breadcrumb">
            <li>
                <!-- <a href="/dashboard">Dashboard</a> -->
                <a href="{{ route('ims.dashboard') }}">Dashboard</a>
            </li>
            <li class="active">DRR Summary</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="tabbable-line boxless tabbable-reversed">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#tab_0" data-toggle="tab">DRR Remarks</a>
                    </li>
                    <li>
                        <a href="#tab_1" data-toggle="tab">Pendings {!! \App\drr::pendingWaybills() !!}</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_0">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="icon-equalizer font-red-sunglo"></i>
                                    <span class="caption-subject font-red-sunglo bold uppercase">DRR Remarks</span>
                                </div>
                            </div>
                            <div class="portlet-body form">
                                <table class="table table-striped table-bordered table-advance table-hover poList">
                                    <thead>
                                        <th style="width:10%;">PO Number</th>
                                        <th style="width:20%;">Supplier</th>
                                        <th style="width:10%;">Waybill</th>
                                        <th style="width:5%;">DRR #</th>
                                        <th style="width:5%;">Qty</th>
                                        <th style="width:10%;">Date Delivered</th>
                                        <th style="width:10%;">DRR Date</th>
                                        <th style="width:10%;">Encoder</th>
                                        <th style="width:15%;">DRR Remarks</th>
                                    </thead>
                                    <tbody id="po_table">
                                        @forelse($datas as $data)
                                            <tr>
                                                <td>{{ $data->pdetails->poNumber }}</td>
                                                <td>{{ $data->pdetails->supplier_name->name }}</td>
                                                <td>{{ $data->waybill }}</td>
                                                <td>{{ $data->drr }}</td>
                                                <td class="text-right">{{ $data->drrQty }}</td>
                                                <td>{{ $data->ldetails->actualDeliveryDate }}</td>
                                                <td>{{ $data->drrDate }}</td>
                                                <td class="text-uppercase">{{ $data->addedBy }}</td>
                                                <td>{!! \App\logistics::drr_remarks($data->waybill) !!}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="10"><center>NO RECORDS FOUND.</center></td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab_1">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="icon-equalizer font-blue-hoki"></i>
                                    <span class="caption-subject font-blue-hoki bold uppercase">Pending Waybills</span>
                                </div>
                            </div>
                            <div class="portlet-body form">
                                <div class="mt-comments">
                                    @forelse($pendings as $pending)
                                        <div class="mt-comment">
                                            <div class="mt-comment-body">
                                                <div class="mt-comment-info">
                                                    <span class="mt-comment-author">WAYBILL # {{ $pending->waybill }}</span>
                                                    <span class="mt-comment-date">{{ date('d F Y',strtotime($pending->actualDeliveryDate)) }}</span>
                                                </div>
                                                <div class="mt-comment-text">{{ $pending->po_details->supplier_name->name }}</div>
                                                <div class="mt-comment-details">
                                                    <span class="mt-comment-status mt-comment-status-pending">PO #{{ $pending->po_details->poNumber }}</span>
                                                    <span class="text-danger pull-right">{{ \Carbon\Carbon::parse($pending->actualDeliveryDate)->diffInDays(today()) }} DAY(S) PENDING</span>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="mt-comment">
                                            <div class="mt-comment-body">
                                                <div class="mt-comment-text"><center>No pending waybills found.</center></div>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
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
    <script src="{{env('APP_URL')}}/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
@endsection