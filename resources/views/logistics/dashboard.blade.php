@extends('layouts.app')

@section('pagecss')
    <link href="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
    <style>
        .highcharts-figure, .highcharts-data-table table {
            min-width: 320px; 
            max-width: 660px;
            margin: 1em auto;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #EBEBEB;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }
        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }
        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }
        .highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
            padding: 0.5em;
        }
        .highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
            background: #f8f8f8;
        }
        .highcharts-data-table tr:hover {
            background: #f1f7ff;
        }

    </style>
@endsection

@section('content')
<div class="page-content">
    <!-- BEGIN BREADCRUMBS -->
    <div class="breadcrumbs">
        <h1>Logistics Dashboard</h1>
    </div>
    <!-- END BREADCRUMBS -->
    
    <div class="row">
        <div class="col-md-12 well">
            <form autocomplete="off" id="form" class="form-inline">
                <div class="form-group">
                    <input style="width: 300px;" name="from" id="from" class="form-control form-control-inline date-picker" type="text" data-date-format="yyyy-mm-dd" data-date-end-date="+0d"/ value="{{ app('request')->input('from') }}" placeholder="From">
                </div>
                <div class="form-group">
                    <input style="width: 300px;" name="to" id="to" class="form-control form-control-inline date-picker" type="text" data-date-format="yyyy-mm-dd" data-date-end-date="+0d" value="{{ app('request')->input('to') }}" placeholder="To" />
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('logistics.dashboard') }}" class="btn default">Reset</a>
            </form>
        </div>
    </div> 

    <div class="row">
        <div class="col-md-6">
            <figure class="highcharts-figure">
                <div id="pie_chart_clearing_time_accuracy"></div>
            </figure>
        </div>
        <div class="col-md-6">
            <figure class="highcharts-figure">
                <div id="pie_chart_shipment_per_location"></div>
            </figure>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <figure class="highcharts-figure">
                <div id="pie_chart_delivery_status"></div>
            </figure>
        </div>
    </div>
    @php
    
        if(!empty($_GET['from'])){
            $from = app('request')->input('from');
            $to   = app('request')->input('to');
        } else {
            $from = \Carbon\Carbon::now()->startOfMonth();
            $to   = \Carbon\Carbon::now()->endOfMonth();
        }

        $datas = \App\PO::deliveries_per_origin($from,$to);
        $arrayData = Array();

        foreach($datas as $data){
            array_push($arrayData,array($data['origin'],floor($data['totalDelivery'])));
        }
        
    @endphp
</div>
@endsection

@section('pagejs')
    <script src="{{env('APP_URL')}}/assets/global/plugins/highcharts/highcharts.js"></script>
    <script src="{{env('APP_URL')}}/assets/global/plugins/highcharts/exporting.js"></script>
    <script src="{{env('APP_URL')}}/assets/global/plugins/highcharts/export-data.js"></script>
    <script src="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
    {{-- <script src="{{env('APP_URL')}}/assets/scripts/home.js"></script> --}}

    <script>
        // Build the chart
            $('#pie_chart_delivery_status').highcharts({
                    
                title: {
                    text: 'Delivery Status'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.y}</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        borderWidth: 3,
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>:<br>{point.percentage:.1f} %<br>VALUE: {point.y}',
                        },
                        showInLegend: false
                    }
                },
                series: [{
                    type: 'pie',
                    name: 'Average',
                    colors: ['#488f31','#de425b'],
                    data: [{
                            name: 'On-Time',
                            y: {{ \App\Logistics::totalOnTimeDeliveries($from,$to) }},
                            sliced: true,
                            selected: true
                        }, {
                            name: 'Late',
                            y: {{ \App\Logistics::totalLateDeliveries($from,$to) }}
                        }]
                }]
            });
        //

        // Build the chart
            $('#pie_chart_clearing_time_accuracy').highcharts({
                    
                title: {
                    text: 'Shipping Clearing Time Accuracy'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        borderWidth: 3,
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>:<br>{point.percentage:.1f} %<br>VALUE: {point.y}',
                        },
                        showInLegend: false
                    }
                },
                series: [{
                    type: 'pie',
                    name: 'Average',
                    colors: ['#003f5c','#de425b'],
                    data: [{
                            name: 'Below threshold',
                            y: {{ \App\logistics::totalOnTimeClearing($from,$to) }},
                            sliced: true,
                            selected: true
                        }, {
                            name: 'Above threshold',
                            y: {{ \App\logistics::totalDelayedClearing($from,$to) }} 
                        }]
                }]
            });
        //

        // Build the chart
            $('#pie_chart_shipment_per_location').highcharts({
                
                title: {
                    text: 'Shipment by Origin'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        borderWidth: 3,
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        },
                        showInLegend: false
                    }
                },
                series: [{
                    type: 'pie',
                    name: 'Total',
                    data: <?php echo json_encode($arrayData);?>
                }]
            });
        //
    </script>
@endsection
