@extends('layouts.app')

@section('pagecss')
    <link href="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        #file_preview{
            padding: 10px;
        }

        #file_preview img{
            width: 150px;
            padding: 5px;
        }
    </style>
@endsection

@section('content')
<div class="page-content">
    <!-- BEGIN BREADCRUMBS -->
    <div class="breadcrumbs">
        <h1>PO DETAILS & SUMMARY</h1>
        @if(auth()->user()->is_an_admin())
            @if($po->status == 'OPEN')
                <ol class="breadcrumb">
                    <a href="javascript:;" class="btn btn-danger" onclick="close_po('{{$po->id}}');">Close PO</a>
                </ol>
            @endif
        @endif
    </div>
    <!-- END BREADCRUMBS -->

    <!-- BEGIN PAGE BASE CONTENT -->
    <div class="profile">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3 profile-info">
                        <h1 class="font-green sbold uppercase">PO #{{ $po->poNumber }}</h1>
                        <p> {{ $po->supplier_name->name }}</p>
                        <ul class="list-inline">
                            <li>
                                <i class="fa fa-map-marker"></i>{{ $po->origin }}</li>
                            <li>
                                <i class="fa fa-money"></i>{{ number_format($po->amount,2) }} {{ $po->currency }}</li>
                            <li>
                                <i class="fa fa-cubes"></i>{{ number_format($po->qty,2) }} {{$po->uom}}</li>
                        </ul>
                        <p>Status : {{ $po->status }}</p>
                        <p>Expected Completion Date : {{ $po->expectedCompletionDate }}</p>
                        <p>Date Needed By End-user : {{ $po->expectedDeliveryDate }}</p>

                    </div>
                    
                    <div class="col-md-3">
                        <p>Order Date : {{ $po->orderDate }}</p>
                        <p>RQ Date : {{ $po->rq_date }}</p>
                        <p>RQ # : {{ $po->rq }}</p>
                        <p>MRS # : {{ $po->mrs_no }}</p>
                        <p class="text-uppercase">Terms : {{ $po->terms }}</p>
                        <p class="text-uppercase">Lead Time : {{ $po->suppliers_lead_time }}</p>
                    </div>

                    <div class="col-md-3">
                        <p class="text-uppercase">Commodity : {{ $po->itemCommodity }}</p>
                        <p class="text-uppercase">Incoterms : {{ $po->incoterms }}</p>
                        <p class="text-uppercase">Delivery Term : {{ $po->delivery_term }}</p>
                        <p class="text-uppercase">Destination Port : {{ $po->destination_port }}</p>
                        <p class="text-uppercase">Encoder : {{ $po->addedBy }}</p>
                        <p class="text-uppercase">Created At : {{ $po->addedDate }}</p>
                    </div>
                    
                    @php
                        $payment_progess   = \App\PO::paymentProgress($po->amount,$po->id);
                        $delivery_progress = \App\PO::deliveryProgress($po->qty,$po->id);
                    @endphp
                    <div class="col-md-3">
                        <div class="dashboard-stat2 bordered">
                            <div class="display">
                                <div class="number">
                                    <h3 class="font-green-sharp">
                                        <span data-counter="counterup" data-value="{{ \App\PO::paidAmount($po->id) }}"></span>
                                        <small class="font-green-sharp">{{$po->currency}}</small>
                                    </h3>
                                    <small>PAID</small>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-credit-card"></i>
                                </div>
                            </div>
                            <div class="progress-info">
                                <div class="progress">
                                    <span style="width: {{ $payment_progess }}%;" class="progress-bar progress-bar-success green-sharp">
                                        <span class="sr-only">{{ $payment_progess }}% progress</span>
                                    </span>
                                </div>
                                <div class="status">
                                    <div class="status-title"> progress </div>
                                    <div class="status-number">{{ $payment_progess }}% </div>
                                </div>
                            </div>
                        </div>
                        <div class="dashboard-stat2 bordered">
                            <div class="display">
                                <div class="number">
                                    <h3 class="font-red-haze">
                                        <span data-counter="counterup" data-value="{{ \App\PO::DeliveredQty($po->id) }}"></span>
                                        <small class="font-red-haze">{{$po->uom}}</small>
                                    </h3>
                                    <small>DELIVERED</small>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-cubes"></i>
                                </div>
                            </div>
                            <div class="progress-info">
                                <div class="progress">
                                    <span style="width: {{ $delivery_progress }}%;" class="progress-bar progress-bar-success red-haze">
                                        <span class="sr-only">{{ $delivery_progress }}% progress</span>
                                    </span>
                                </div>
                                <div class="status">
                                    <div class="status-title"> progress </div>
                                    <div class="status-number">{{ $delivery_progress }}% </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                           <p>Update Receivers : {{ $po->email_receivers }}</p>
                    </div>
                </div>

                <div class="tabbable-line tabbable-custom-profile">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#payments" data-toggle="tab">Payments @if($total_payment > 0) <span class="badge badge-success">{{ $total_payment }} @endif</span></a>
                        </li>
                        <li>
                            <a href="#shipments" data-toggle="tab">Shipments @if($total_shipment > 0) <span class="badge badge-success">{{ $total_shipment }} @endif</span></a>
                        </li>
                        <li>
                            <a href="#deliveries" data-toggle="tab">Deliveries @if($total_deliveries > 0) <span class="badge badge-success">{{ $total_deliveries }} @endif</span></a>
                        </li>
                        <li>
                            <a href="#files" data-toggle="tab">PO Files</a>
                        </li>
                        <li>
                            <a href="" data-toggle="tab">Logs <span class="text-danger">(Under Maintenance)</span></a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="payments">
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-advance table-hover">
                                    <thead>
                                        <tr>
                                            <th>Payment Date</th>
                                            <th class="hidden-xs">Amount</th>
                                            <th>Actual Payment Date</th>
                                            <th>Status</th>
                                            <th>Payment Remarks</th>
                                            <th>Remarks</th>
                                            <th>File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($payments as $payment)
                                        <tr>
                                            <td>{{ $payment->paymentDate }}</td>
                                            <td class="hidden-xs text-right">{{ number_format($payment->amount,2) }}</td>
                                            <td>{{ $payment->actualPaymentDate }}</td>
                                            <td>
                                                @if($payment->isPaid == 1)
                                                    <span class="label label-success label-sm">PAID</span>
                                                @else
                                                    <span class="label label-warning label-sm">UNPAID</span>
                                                @endif
                                            </td>
                                            <td>{{ $payment->payment_type }}</td>
                                            <td class="text-uppercase">{{ $payment->remarks }}</td>
                                            <td>
                                                @php
                                                    $dir = '\\\\ftp\\FTP\\APP_UPLOADED_FILES\\ims\\'.$po->poNumber.'\\payment\\'.$payment->id.'\\';
                                                
                                                    if(is_dir($dir)){
                                                        $files = scandir($dir);
                                                        for($i=0; $i< count($files);$i++){
                                                            if($files[$i] != '.' && $files[$i] != '..'){
                                                    @endphp
                                                            <a onclick="preview_payment_file('{{$po->poNumber}}','{{$files[$i]}}','{{$payment->id}}');" href="#">{{$files[$i]}}</a>
                                                    @php
                                                            }      
                                                        }
                                                    }
                                                @endphp
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="6"><center>NO PAYMENTS FOUND.</center></td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--tab-pane-->
                        <div class="tab-pane" id="shipments">
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-advance table-hover">
                                    <thead>
                                        <tr>
                                            <th>Waybill</th>
                                            <th class="hidden-xs">Actual Date Manufactured</th>
                                            <th>Date Depart</th>
                                            <th>Date Arrived</th>
                                            <th>Custom Start</th>
                                            <th>Custom Cleared</th>
                                            <th>Date Delivered</th>
                                            <th>Date Committed</th>
                                            <th>Status</th>
                                            <th>Type</th>
                                            <th>Files</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($shipments as $shipment)
                                        <tr>
                                            <td>{{ $shipment->waybill }}</td>
                                            <td class="hidden-xs">{{ $shipment->actualManufacturingDate }}</td>
                                            <td>{{ $shipment->departure_dt }}</td>
                                            <td>{{ $shipment->portArrivalDate }}</td>
                                            <td>{{ $shipment->customStartDate }}</td>
                                            <td>{{ $shipment->customClearedDate }}</td>
                                            <td>{{ $shipment->actualDeliveryDate }}</td>
                                            <td>{{ $shipment->expectedDeliveryDate }}</td>
                                            <td class="text-uppercase">{{ $shipment->status }}</td>
                                            <td class="text-uppercase">{{ $shipment->log_type }}</td>
                                            <td></td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="11"><center>NO SHIPMENTS FOUND.</center></td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--tab-pane-->

                        <!--tab-pane-->
                        <div class="tab-pane" id="deliveries">
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-advance table-hover">
                                    <thead>
                                        <tr>
                                            <th class="hidden-xs">Waybill</th>
                                            <th>DRR #</th>
                                            <th>DRR Amount</th>
                                            <th>DRR Qty</th>
                                            <th>DRR Date</th>
                                            <th>Invoice</th>
                                            <th>File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($deliveries as $delivery)
                                        <tr>
                                            <td class="hidden-xs text-uppercase">{{ $delivery->waybill }}</td>
                                            <td>{{ $delivery->drr }}</td>
                                            <td>{{ number_format($delivery->drrAmount,2) }}</td>
                                            <td>{{ number_format($delivery->drrQty,2) }}</td>
                                            <td>{{ $delivery->drrDate }}</td>
                                            <td>{{ $delivery->invoice }}</td>
                                            <td>
                                                @php
                                                    $dir = '\\\\ftp\\FTP\\APP_UPLOADED_FILES\\ims\\'.$po->poNumber.'\\mcd\\'.$delivery->id.'\\';
                                                
                                                    if(is_dir($dir)){
                                                        $files = scandir($dir);
                                                        for($i=0; $i< count($files);$i++){
                                                            if($files[$i] != '.' && $files[$i] != '..'){
                                                    @endphp
                                                            <a onclick="preview_drr_file('{{$po->poNumber}}','{{$files[$i]}}','{{$delivery->id}}');" href="#">{{$files[$i]}}</a><br>
                                                    @php
                                                            }      
                                                        }
                                                    }
                                                @endphp
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="7"><center>NO DELIVERY RECEIVING REPORT FOUND.</center></td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--tab-pane-->

                        <!--tab-pane-->
                        <div class="tab-pane" id="files">
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-5">
                                        <table class="table table-striped table-bordered table-advance table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="hidden-xs">File Name</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr><td> ****** OLD PO FILES ******</td></tr>
                                                {!!$old_files!!}  
                                                <tr><td> ****** PO FILES ******</td></tr>   
                                                @php
                                                    $dir = '\\\\ftp\\FTP\\APP_UPLOADED_FILES\\ims\\'.$po->poNumber.'\\po\\';
                                                
                                                    if(is_dir($dir)){
                                                        $files = scandir($dir);
                                                        for($i=0; $i< count($files);$i++){
                                                            if($files[$i] != '.' && $files[$i] != '..'){
                                                    @endphp
                                                            <tr>
                                                                <td>
                                                                    <a onclick="preview_po_file('{{$files[$i]}}','{{$po->poNumber}}');" 
                                                                    href="#">{{$files[$i]}}</a>
                                                                </td>
                                                            </tr>
                                                    @php
                                                            }      
                                                        }
                                                    }
                                                @endphp
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-7">
                                        <div class="form-row">
                                            <form autocomplete="off" action="{{ route('upload.files') }}" method="POST" id="formid" enctype="multipart/form-data">
                                                @csrf
                                                <div class="col-md-9">
                                                    <input type="hidden" name="po" value="{{$po->poNumber}}">
                                                    <input type="file" id="uploadFile" class="form-control" name="uploadFile[]" multiple/>
                                                </div>
                                                <div class="col-md-3">
                                                    <button class="btn btn-primary">Upload</button>
                                                </div>
                                            </form>
                                        </div>
                                        
                                        <div id="file_preview"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="close_po" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('manual.close.po') }}" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Confirmation</h4>
                    </div>
                    <div class="modal-body"> You're about to close this PO. Do you want to continue ?
                        <input type="hidden" name="cid" id="cid">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn red">Yes, Close this PO</button>
                    </div>
                </div>
            </form>  
        </div>
    </div>
</div>
@endsection

@section('pagejs')
    <script type="text/javascript" src="{{env('APP_URL')}}/assets/global/scripts/datatable.js"></script>
    <script type="text/javascript" src="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.js"></script>
    <script type="text/javascript" src="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"></script>
    <script type="text/javascript" src="{{env('APP_URL')}}/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>

    <script type="text/javascript">

        function close_po(id)
        {
            $('#close_po').modal('show');
            $('#cid').val(id);
        }

        $("#uploadFile").change(function(){
            $('#file_preview').html("");

            var total_file=document.getElementById("uploadFile").files.length;

            for(var i=0;i<total_file;i++){
                $('#file_preview').append("<img src='"+URL.createObjectURL(event.target.files[i])+"'>");
            }

        });

        $(document).ready(function(){
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });
        });
        
        function preview_po_file(f,p) {

            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0');
            var yyyy = today.getFullYear();

            today = yyyy + '-' + mm + '-' + dd;

            $.ajax({
                type : 'post',
                url  : '{{env("APP_URL")}}/copyFile',
                data : {
                    'po'        : p,
                    'fileName'  : f,
                    '_token'    : $('input[name=_token]').val(),
                },
                type : 'POST',
                success : function (data) {
                    window.open('{!!asset("storage/'+today+'/")!!}/'+f,"_blank")
                }
            });
        }

        function show_upload_modal()
        {
            $('#upload_modal').modal('show');
        }

        function preview_payment_file(pn,f,p) {

            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0');
            var yyyy = today.getFullYear();

            today = yyyy + '-' + mm + '-' + dd;

            $.ajax({
                type : 'post',
                url  : '{{env("APP_URL")}}/payment/preview-file',
                data : {
                    'po'        : pn,
                    'pm'        : p,
                    'fileName'  : f,
                    '_token'    : $('input[name=_token]').val(),
                },
                type : 'POST',
                success : function (data) {
                    window.open('{!!asset("storage/'+today+'/")!!}/'+f, '_blank');
                }
            });
        }

        function preview_drr_file(pn,f,did) {

            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0');
            var yyyy = today.getFullYear();

            today = yyyy + '-' + mm + '-' + dd;

            $.ajax({
                type : 'post',
                url  : '{{env("APP_URL")}}/drr/copyFile',
                data : {
                    'po'        : pn,
                    'did'       : did,
                    'fileName'  : f,
                    '_token'    : $('input[name=_token]').val(),
                },
                type : 'POST',
                success : function (data) {
                    window.open('{!!asset("storage/'+today+'/")!!}/'+f, '_blank');
                }
            });
        }

        
    </script>
@endsection


