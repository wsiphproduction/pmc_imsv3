@extends('layouts.app')

@section('pagecss')
    <link href="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />

    <link href="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />

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
            <form autocomplete="off" id="form" class="form-inline well">
                <div class="form-group">
                    <input style="width: 300px;" name="from" id="from" class="form-control form-control-inline date-picker" type="text" data-date-format="yyyy-mm-dd" data-date-end-date="+0d"/ value="{{ app('request')->input('from') }}" placeholder="From">
                </div>
                <div class="form-group">
                    <input style="width: 300px;" name="to" id="to" class="form-control form-control-inline date-picker" type="text" data-date-format="yyyy-mm-dd" data-date-end-date="+0d" value="{{ app('request')->input('to') }}" placeholder="To" />
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('receiving.summary') }}" class="btn default">Reset</a>
            </form>
        </div>
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
                                    <th style="width: 10%;">DRR #</th>
                                    <th style="width: 10%;">Amount</th>
                                    <th style="width: 10%;">Qty</th>
                                    <th style="width: 8%;">DRR Date</th>
                                    <th style="width: 7%;">Encoder</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($datas as $data)
                                    <tr>
                                        <td>{{ ($datas->currentpage()-1) * $datas->perpage() + $loop->index + 1 }}</td>
                                        <td>{{ $data->pdetails->poNumber }}</td>
                                        <td class="text-uppercase">{{ $data->pdetails->supplier_name->name }}</td>
                                        <td>{{ $data->waybill }}</td>
                                        <td>{{ $data->drr }}</td>
                                        <td class="text-right">{{ number_format($data->drrAmount,2) }}</td>
                                        <td class="text-right">{{ number_format($data->drrQty,2) }}</td>
                                        <td>{{ $data->drrDate }}</td>
                                        <td class="text-uppercase">{{ $data->addedBy }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8"><center>NO DELIVERIES FOUND.</center></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p>Showing {{$datas->firstItem()}} to {{$datas->lastItem()}} of {{$datas->total()}} items</p>
                        </div>
                        <div class="col-md-6">
                            <span class="pull-right">{{ $datas->appends($param)->links() }}</span>
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

            if($('#from').val() == '' && $('#to').val() == ''){
                
                $('#prompt-no-value').modal('show');
                
                return false;

            } else {

                filter();

            }
            
        });

        /*** generate the parameters for filtering of pages ***/
        function filter(){

            var url = '';
            url = 'from='+$('#from').val()+'&to='+$('#to').val();

            window.location.href = "{{ route('filter.drr.summary') }}?"+url;
        }
    </script>
@endsection