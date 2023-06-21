@extends('layouts.app')

@section('pagecss')
    <link href="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="page-content">
    <!-- BEGIN BREADCRUMBS -->
    <div class="breadcrumbs">
        <h1>PO Delivery Aging</h1>
        <ol class="breadcrumb">
            <li>
                <!-- <a href="/dashboard">Dashboard</a> -->
                <a href="{{ route('ims.dashboard') }}">Dashboard</a>
            </li>
            <li>
                <a href="#">Reports</a>
            </li>
            <li class="active">Delivery Aging</li>
        </ol>
    </div>
    <!-- END BREADCRUMBS -->
                    
    <!-- BEGIN PAGE BASE CONTENT -->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN SAMPLE TABLE PORTLET-->
            <div class="portlet">

                <div class="portlet-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-advance table-hover poList">
                            <thead>
                                <th style="width: 3%;">#</th>
                                <th style="width: 7%;">PO Reference</th>
                                <th style="width: 30%;">Supplier</th>
                                <th style="width: 10%;">Date Ordered</th>
                                <th style="width: 10%;">Inco Terms</th>
                                <th style="width: 10%;">Expedted Delivery Date</th>
                                <th style="width: 10%;">Actual Delivery Date</th>
                                <th style="width: 10%;">Delivery Aging</th>
                                <th style="width: 10%;">Terms</th>
                            </thead>
                            <tbody id="payments_table">
                                @php
                                    $grouped = $data->groupBy('id');
                                    $grouped->toArray();
                                @endphp

                                @forelse($grouped as $d)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $d[0]['poNumber'] }}</td>
                                        <td>{{ $d[0]['supplier'] }}</td>
                                        <td>{{ $d[0]['orderDate'] }}</td>
                                        <td>{{ $d[0]['incoterms'] }}</td>
                                        <td>{{ $d[0]['expectedCompletionDate'] }}</td>
                                        <td>{{ $d[0]['actualDeliveryDate'] }}</td>
                                        <td>{{ $d[0]['AgingDeliveryDate'] }} day(s)</td>
                                        <td>{{ $d[0]['delivery_term'] }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6"><center>No PO Records Found</center></td></tr>
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
    <script src="{{env('APP_URL')}}/assets/global/scripts/datatable.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>

    <script>
        $(document).ready(function(){
            
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });
        });

        $(document).ready(function(){
            $('#payment_form').submit(function(e){
                e.preventDefault();
                $.ajax({
                    type: "GET",
                    url: "{{ route('generate.payments') }}",
                    data: $('#payment_form').serialize(),
                    success: function( response ) {
                        $('#payments_table').html(response);  
                    }
                });
            });
        });
    </script>
@endsection
