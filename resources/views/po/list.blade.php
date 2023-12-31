@extends('layouts.app')

@section('pagecss')
    <link href="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />

    <style>
        .poList tbody tr td  { font-size: 11px; }
    </style>
@endsection

@section('content')
<div class="page-content">
    <!-- BEGIN BREADCRUMBS -->
    <div class="breadcrumbs">
        <h1>PO Summary List</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('ims.dashboard') }}">Dashboard</a>
            </li>
            <li class="active">PO Summary List</li>
        </ol>
    </div>
    <!-- END BREADCRUMBS -->
                    
    <!-- BEGIN PAGE BASE CONTENT -->
    <div class="row">
        <div class="col-md-8 pull-right">
            <form autocomplete="off" id="form">
                <table width="100%">
                    <tr>
                        <td><input type="text" id="poNumber" class="form-control" placeholder="Search PO Number" @if(isset($_GET['poNumber'])) value="{{$_GET['poNumber']}}" @endif></td>
                        <td>
                            <select name="supplier" id="supplier" class="form-control" style="margin-right:10px;">
                                <option value="" selected="selected"> Filter Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{$supplier->id}}" @if(isset($_GET['supplier']) && $_GET['supplier'] == $supplier->id) selected="selected" @endif>{{$supplier->name}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><button class="btn blue" type="submit">Search</button></td>
                        <td><a href="{{route('po.list')}}" class="btn yellow">Reset</a></td>
                    </tr>
                </table>
               
            </form>
        </div>
    </div>

    <div class="row">
    <div class="col-md-12">
    <!-- BEGIN SAMPLE TABLE PORTLET-->
    <div class="portlet">

    <div class="portlet-body">
    <div class="table-scrollable">
        <table class="table table-striped table-bordered table-advance table-hover poList">
            <thead>
                <th>#</th>
                <th style="width:  5%;">PO Number</th>
                <th style="width: 20%;"><center>Supplier</center></th>
                <th style="width: 20%;"><center>Delivery Progress</center>
                <th style="width: 20%;"><center>Payment Progress</center></th>
                <th style="width: 10%;">Aging Days</th>
                <th style="width: 5%;">Status</th>      
                <th style="width: 20%;"></th>
            </thead>
            <tbody id="po_table">
                @forelse($collection as $data)
                    @php
                        $payment_progess   = \App\PO::paymentProgress($data->amount,$data->id);
                        $delivery_progress = \App\PO::deliveryProgress($data->qty,$data->id);
                    @endphp
                    <tr>
                        <td>{{ ($collection->currentpage()-1) * $collection->perpage() + $loop->index + 1 }}</td>
                        <td style="display: none;">
                            <input type="text" id="pon" value="{{$data->poNumber }}">
                            <input type="text" id="poid" value="{{$data->id }}">

                            <input type="text" id="payment" value="{{ $payment_progess }}">
                            <input type="text" id="delivery" value="{{ $delivery_progress }}"> 
                        </td>
                        <td class="highlight">{{ $data->poNumber }}</td>
                        <td>{{ $data->supplier_name->name }}</td>

                        <td>
                            &nbsp;<span class="pull-right" style="font-size: 11px;">{{ $delivery_progress }}% Delivered</span>
                            <div class="progress progress-bar-danger progress-sm">
                                <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="{{ $delivery_progress }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $delivery_progress }}%">
                                </div>
                            </div>
                        </td>

                        <td>
                            &nbsp;<span class="pull-right" style="font-size: 11px;">{{ $payment_progess }}% Paid</span>
                            <div class="progress progress-bar-danger progress-sm">
                                <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="{{ $payment_progess }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $payment_progess }}%">
                                </div>
                            </div>
                        </td>

                        <td>
                            <ul class="list-unstyled">
                                <li>
                                    <i class="text-danger">*</i> Order: 
                                    {{ \Carbon\Carbon::parse($data->orderDate)->diffInDays(today(), false) }}
                                </li>

                                <li>
                                    <i class="text-danger">*</i> Completion: 
                                    {{ \Carbon\Carbon::parse($data->expectedCompletionDate)->diffInDays(today(), false) }}
                                </li>
                            </ul>
                        </td>

                        <td><span class="label label-sm label-default">{{ $data->status }}</span></td>

                        <td id="btn{{ $data->id }}">
             @if($data->status == 'OPEN')
            <div class="btn-toolbar margin-bottom-2">
                <div class="btn-group btn-group-sm btn-group-solid">                                                        
                    @if($edit)
                    <a href="{{env('APP_URL')}}/ims/po/details/{{ $data->id }}" class="btn btn-sm purple tooltips" data-container="body" data-placement="top" data-original-title="View PO Details">
                    @else
                    <button disabled href="{{env('APP_URL')}}/ims/po/details/{{ $data->id }}" class="btn btn-sm purple tooltips" data-container="body" data-placement="top" data-original-title="View PO Details">
                    @endif
                        <i class="fa fa-eye"></i>
                    </button>
                    @if(auth()->user()->is_an_admin())
                        @if($edit)
                        <a href="#close_po" data-poid="{{ $data->id }}" data-toggle="modal" class="btn btn-sm btn-warning tooltips close-po" data-container="body" data-placement="top" data-original-title="Close PO">
                            <i class="fa fa-times"></i>
                        </a>
                        @else
                        <button disabled href="#close_po" data-poid="{{ $data->id }}" data-toggle="modal" class="btn btn-sm btn-warning tooltips close-po" data-container="body" data-placement="top" data-original-title="Close PO">
                            <i class="fa fa-times"></i>
                        </button>@endif
                        @if($delete)
                        <a href="#delete_po" data-poid="{{ $data->id }}" data-toggle="modal" class="btn btn-sm red delete-po tooltips" data-container="body" data-placement="top" data-original-title="Delete PO">
                            <i class="fa fa-trash"></i>
                        </a>
                        @else
                        <button disabled href="#delete_po" data-poid="{{ $data->id }}" data-toggle="modal" class="btn btn-sm red delete-po tooltips" data-container="body" data-placement="top" data-original-title="Delete PO">
                            <i class="fa fa-trash"></i>
                        </button>
                        @endif
                    @endif
                    @if($edit)
                    @if(auth()->user()->checkPermission('update_po'))
                        <a href="{{env('APP_URL')}}/ims/po/edit/{{ $data->id }}" class="btn btn-sm btn-primary tooltips" data-container="body" data-placement="top" data-original-title="Update PO">
                            <i class="fa fa-edit"></i>
                        </a>
                    @endif
                    
                    @if(auth()->user()->checkPermission('add_drr'))
                        @if($data->delivery_term == 'consignment items')
                            <a href="{{ route('create.services',$data->id) }}" class="btn btn-sm btn-primary tooltips" data-container="body" data-placement="top" data-original-title="Add DRR">
                                <i class="fa fa-plus"></i>
                            </a>
                        @endif
                    @endif

                    @if(auth()->user()->checkPermission('update_payment'))
                        @if(\App\PaymentSchedule::count_payments($data->id) >= 1)
                            <a href="{{env('APP_URL')}}/ims/payment-schedule/edit/{{$data->id}}" class="btn btn-sm btn-success tooltips" data-container="body" data-placement="top" data-original-title="Update Payment">
                                <i class="fa fa-edit"></i>
                            </a>
                        @endif
                    @endif
                    
                    @if(auth()->user()->checkPermission('create_shipment_schedule'))
                        @if(\App\logistics::count_shipments($data->id) >= 1)
                                <a href="{{ route('view.shipment.schedule',$data->id) }}" class="paid btn btn-sm btn-primary tooltips" data-container="body" data-placement="top" data-original-title="View Shipment Schedule">
                                <i class="fa fa-ship"></i>
                            </a>
                        @else
                            <a href="{{ route('create.shipment.schedule',$data->id) }}" class="paid btn btn-sm btn-success tooltips" data-container="body" data-placement="top" data-original-title="Create Shipment Schedule">
                                <i class="fa fa-plus"></i>
                            </a>   
                        @endif

                        @if(\App\PaymentSchedule::count_payments($data->id) >= 1)
                            <a href="{{env('APP_URL')}}/ims/payment-schedule/edit/{{$data->id}}" class="btn btn-sm btn-warning tooltips" data-container="body" data-placement="top" data-original-title="Update Payment">
                                <i class="fa fa-edit"></i>
                            </a>
                        @endif
                    @endif
                    @else
                    @if(auth()->user()->checkPermission('update_po'))
                        <button disabled href="{{env('APP_URL')}}/ims/po/edit/{{ $data->id }}" class="btn btn-sm btn-primary tooltips" data-container="body" data-placement="top" data-original-title="Update PO">
                            <i class="fa fa-edit"></i>
                        </button>
                    @endif
                    
                    @if(auth()->user()->checkPermission('add_drr'))
                        @if($data->delivery_term == 'consignment items')
                            <button disabled href="{{ route('create.services',$data->id) }}" class="btn btn-sm btn-primary tooltips" data-container="body" data-placement="top" data-original-title="Add DRR">
                                <i class="fa fa-plus"></i>
                            </button>
                        @endif
                    @endif

                    @if(auth()->user()->checkPermission('update_payment'))
                        @if(\App\PaymentSchedule::count_payments($data->id) >= 1)
                            <button disabled href="{{env('APP_URL')}}/ims/payment-schedule/edit/{{$data->id}}" class="btn btn-sm btn-success tooltips" data-container="body" data-placement="top" data-original-title="Update Payment">
                                <i class="fa fa-edit"></i>
                            </button>
                        @endif
                    @endif
                    
                    @if(auth()->user()->checkPermission('create_shipment_schedule'))
                        @if(\App\logistics::count_shipments($data->id) >= 1)
                                <button disabled href="{{ route('view.shipment.schedule',$data->id) }}" class="paid btn btn-sm btn-primary tooltips" data-container="body" data-placement="top" data-original-title="View Shipment Schedule">
                                <i class="fa fa-ship"></i>
                            </button>
                        @else
                            <button disabled href="{{ route('create.shipment.schedule',$data->id) }}" class="paid btn btn-sm btn-success tooltips" data-container="body" data-placement="top" data-original-title="Create Shipment Schedule">
                                <i class="fa fa-plus"></i>
                            </button>   
                        @endif

                        @if(\App\PaymentSchedule::count_payments($data->id) >= 1)
                            <button disabled href="{{env('APP_URL')}}/ims/payment-schedule/edit/{{$data->id}}" class="btn btn-sm btn-warning tooltips" data-container="body" data-placement="top" data-original-title="Update Payment">
                                <i class="fa fa-edit"></i>
                            </button>
                        @endif
                    @endif
                    @endif
                    </div>
                </div>
            @else                                                
                @if($edit)
                <a href="{{env('APP_URL')}}/ims/po/details/{{ $data->id }}" class="btn btn-sm purple tooltips" data-container="body" data-placement="top" data-original-title="View PO Summary"><i class="fa fa-eye"></i></a>
                @else
                <button disabled href="{{env('APP_URL')}}/ims/po/details/{{ $data->id }}" class="btn btn-sm purple tooltips" data-container="body" data-placement="top" data-original-title="View PO Summary"><i class="fa fa-eye"></i></button>
                @endif
            @endif
        </td>
    </tr>
                @empty
                    <tr><td colspan="8" class="text-uppercase"><center>No PO Records Found</center></td></tr>
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
        </div>
    </div>
</div>
@include('modal.po')
@endsection

@section('pagejs')
    <script src="{{env('APP_URL')}}/assets/global/scripts/datatable.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/pages/scripts/table-datatables-buttons.min.js" type="text/javascript"></script>

    <script>
        $(document).on('click', '.close-po', function() {
            $('#cid').val($(this).data('poid'));
        });

        $(document).on('click', '.delete-po', function() {
            $('#did').val($(this).data('poid'));
        });

        /*** handles the click function on filter classes, redirects it to function filter ***/
        $('#form').submit(function(e){
            e.preventDefault(); 
            //$('#form').submit();
            filter();
        });

        /*** generate the parameters for filtering of pages ***/
        function filter(){

            var url = '';
                url += '&poNumber='+$('#poNumber').val()+'&supplier='+$('#supplier').val();
                //url += '&supplier='+$('#supplier').val();


            window.location.href = "{{ route('po.search') }}?"+url;
        }
    </script>
@endsection