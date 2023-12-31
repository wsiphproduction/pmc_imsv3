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
        <h1>PO Completion 
            @if(($_GET['from'] ?? '') != '') 
                <small>From: {{ date('Y-m-d',strtotime(app('request')->input('from'))) }} - To: {{ date('Y-m-d',strtotime(app('request')->input('to'))) }}</small> 
            @else 
                <small>From: {{ \Carbon\Carbon::now()->startOfWeek()->format('Y-m-d') }} - To: {{ \Carbon\Carbon::now()->endOfWeek()->format('Y-m-d') }}</small> 
            @endif
        </h1>
        <ol class="breadcrumb">
            <li>
                <!-- <a href="/dashboard">Dashboard</a> -->
                <a href="{{ route('ims.dashboard') }}">Dashboard</a>
            </li>
            <li class="active">PO Completion</li>
        </ol>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="portlet">
                <div class="portlet-body">
                    <div class="table-scrollable">
                        <table class="table table-striped table-bordered table-advance table-hover poList">
                            <thead>
                                <th>#</th>
                                <th>PO Reference</th>
                                <th>Supplier</th>
                                <th>Date Ordered</th>
                                <th>Date Completion</th>
                                <th>IncoTerms</th>
                                <th>Payment Progress</th>
                                <th>Shipment Progress</th>
                                <th>Delivery Progress</th>
                                <th>Status</th>
                                <th></th>
                            </thead>
                            <tbody>
                                @forelse($collection as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->poNumber }}</td>
                                        <td>{{ $data->supplier_name->name }}</td>
                                        <td>{{ $data->orderDate }}</td>
                                        <td>{{ $data->expectedCompletionDate }}</td>
                                        <td>{{ $data->incoterms }}</td>
                                        <td>{{ \App\PO::paymentProgress($data->amount,$data->id) }}% PAID</td>
                                        <td>{{ \App\logistics::shipment_status($data->id) }} SHIPPED</td>
                                        <td>{{ \App\PO::deliveryProgress($data->qty,$data->id) }}% DELIVERED</td>
                                        <td>{{ $data->status }}</td>
                                        <td>                                            
                                            @if($edit)
                                            <a href="{{env('APP_URL')}}/ims/po/details/{{ $data->id }}" class="btn btn-sm purple"><i class="fa fa-eye"></i></a>
                                            @else
                                            <button disabled href="{{env('APP_URL')}}/ims/po/details/{{ $data->id }}" class="btn btn-sm purple"><i class="fa fa-eye"></i></button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty

                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @php
                        $date = new \Carbon\Carbon(app('request')->input('from'));

                        if(isset($date)){
                            $firstDayNextWeek = $date->startOfWeek()->addDays(7);
                        } else {
                            $firstDayNextWeek = \Carbon\Carbon::today()->startOfWeek()->addDays(7);
                        }
                        
                    @endphp

                    @php
                        $date = new \Carbon\Carbon(app('request')->input('from'));

                        if(isset($date)){
                            $firstDayPrevWeek = $date->startOfWeek()->subDays(7);
                        } else {
                            $firstDayPrevWeek = \Carbon\Carbon::today()->startOfWeek()->subDays(7);
                        }
                        
                    @endphp
                </div>
            </div>
        </div>
    </div>
    {{-- Start Pagination --}}
        <div class="row">
            <div class="col-md-12">
                <div style="padding-left: 0px;" class="col-md-6">
                    <form id="prev_records">
                        @csrf
                        <input type="hidden" name="prev_from" id="prev_from" value="{{ $firstDayPrevWeek }}">
                        <input type="hidden" name="prev_to" id="prev_to" value="{{ $firstDayPrevWeek->endOfWeek() }}">
                        <button class="btn blue pull-left" type="submit">
                            <i class="fa fa-angle-left"></i> Previous</button>
                    </form>
                </div>
                <div style="padding-right: 0px;" class="col-md-6">
                    <form id="next_records">
                        @csrf
                        <input type="hidden" name="next_from" id="next_from" value="{{ $firstDayNextWeek }}">
                        <input type="hidden" name="next_to" id="next_to" value="{{ $firstDayNextWeek->endOfWeek() }}">
                        <button class="btn blue pull-right" type="submit">
                            Next <i class="fa fa-angle-right"></i></button>
                    </form>
                </div>
            </div>
        </div>
        {{-- End Pagination --}}
</div>
@include('modal.payments')
@endsection

@section('pagejs')
<script src="{{env('APP_URL')}}/assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>

<script>

    /*** handles the click function on filter classes, redirects it to function filter ***/

    $('#next_records').submit(function(e){
        e.preventDefault();
        var type = 'next';

        filter(type);

    });

    $('#prev_records').submit(function(e){
        e.preventDefault();
        var type = 'prev';

        filter(type);

    });

    /*** generate the parameters for filtering of pages ***/
    function filter(type){

        var url = '';
        if(type == 'next'){

            url = 'from='+$('#next_from').val()+'&to='+$('#next_to').val();

        } else {

            url = 'from='+$('#prev_from').val()+'&to='+$('#prev_to').val();
        }

        window.location.href = "{{ route('filter.completion.date') }}?"+url;
    }
</script>
@endsection