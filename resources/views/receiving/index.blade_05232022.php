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
        <h1>Pending Waybills</h1>
        <ol class="breadcrumb">
            <li>
                <!-- <a href="/dashboard">Dashboard</a> -->
                <a href="{{ route('ims.dashboard') }}">Dashboard</a>
            </li>
            <li class="active">Pending Waybills</li>
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
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 10%;">PO Number</th>
                                    <th style="width: 30%;">Supplier</th>
                                    <th style="width: 10%;">Waybill</th>
                                    <th style="width: 10%;"> Incoterms </th>
                                    <th style="width: 10%;">Delivery Type</th>
                                    <th style="width: 7%;">Date Delivered</th>
                                    <th style="width: 8%;">Delivery Term</th>
                                    <th style="width: 10%;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $l = 0; @endphp
                                @forelse($datas as $data)
                                    @php
                                        $balance = \App\PO::drrAmountBalance($data->poId);

                                    @endphp
                                    @if($balance > 0)
                                        @php $l++; @endphp
                                        <tr>
                                            <td>{{ $l }}</td>
                                            <td><a target="_blank" href="{{env('APP_URL')}}/ims/po/details/{{ $data->po_details->id }}">{{ $data->po_details->poNumber }}</a></td>
                                            <td class="text-uppercase">{{ $data->po_details->supplier_name->name }}</td>
                                            <td>{{ $data->waybill }}</td>
                                            <td>{{ $data->po_details->incoterms }}</td>
                                            <td class="text-uppercase">{{ $data->log_type }}</td>
                                            <td>{{ $data->actualDeliveryDate }}</td>
                                            <td class="text-uppercase">{{ $data->po_details->delivery_term }}</td>
                                            <td>
                                                @if($create)
                                                <a href="{{ route('create.drr',['waybill' => str_replace('/','qwjz',$data->waybill), 'po' => $data->po_details->id]) }}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Process DRR</a>
                                                @else
                                                <button disabled href="{{ route('create.drr',['waybill' => str_replace('/','qwjz',$data->waybill), 'po' => $data->po_details->id]) }}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Process DRR</button>
                                                @endif
                                                @if(Auth::user()->domainAccount == 'dtablante')
                                                @if($edit)
                                                    &nbsp;<a href="{{env('APP_URL')}}/ims/payment-schedule/edit/{{$data->po_details->id}}" class="btn btn-sm btn-info"><i class="fa fa-pencil"></i> Update Payments</a>
                                                    
                                                    @else
                                                    &nbsp;<button disabled href="/ims/payment-schedule/edit/{{$data->po_details->id}}" class="btn btn-sm btn-info"><i class="fa fa-pencil"></i> Update Payments</button>
                                                   @endif
                                                    @endif
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr><td colspan="9"><center>NO DELIVERIES FOUND.</center></td></tr>
                                @endforelse
                            </tbody>
                        </table>
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
@endsection