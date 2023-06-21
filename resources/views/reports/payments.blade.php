@extends('layouts.app')

@section('pagecss')
    <link href="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="page-content">
    <!-- BEGIN BREADCRUMBS -->
    <div class="breadcrumbs">
        <h1>Payments Report</h1>
        <ol class="breadcrumb">
            <li>
                <!-- <a href="/dashboard">Dashboard</a> -->
                <a href="{{ route('ims.dashboard') }}">Dashboard</a>
            </li>
            <li>
                <a href="#">Reports</a>
            </li>
            <li class="active">Payments</li>
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
                                <tr>
                                    @csrf
                                    <th colspan="7">
                                        <form autocomplete="off" id="payment_form" class="form-inline">
                                            <center>
                                                <div class="form-group mb-2">
                                                    <label for="from" class="sr-only">Email</label>
                                                    <input type="date" name="from" class="form-control" id="from">
                                                </div>
                                                <div class="form-group mx-sm-3 mb-2">
                                                    <label for="inputPassword2" class="sr-only">to</label>
                                                    <input type="date" name="to" class="form-control" id="to">
                                                </div>
                                                <button type="submit" class="btn btn-sm btn-primary mb-2"><i class="fa fa-filter"></i></button>
                                            </center>
                                        </form>
                                    </th>
                                </tr>
                                <tr>
                                    <th style="width: 3%;">#</th>
                                    <th style="width: 10%;">PO Reference</th>
                                    <th style="width: 40%;">Supplier</th>
                                    <th style="width: 10%;">Payment Date</th>
                                    <th style="width: 7%;">Currency</th>
                                    <th style="width: 10%;">Amount</th>
                                    <th style="width: 10%;">Status</th>
                                </tr>
                            </thead>
                            <tbody id="payments_table">
                                @forelse($payments as $payment)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $payment->poNumber }}</td>
                                        <td>{{ $payment->name }}</td>
                                        <td>{{ $payment->paymentDate }}</td>
                                        <td>{{ $payment->currency }}</td>
                                        <td class="text-right">{{ number_format($payment->amount,2) }}</td>
                                        <td>{!! \App\PaymentSchedule::is_paid($payment->isPaid) !!}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7"><center>No Payments for this month</center></td>
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