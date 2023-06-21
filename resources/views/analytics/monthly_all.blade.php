@extends('layouts.app')

    
@section('pagecss')
    <script type="text/javascript" src="https://cdn.fusioncharts.com/fusioncharts/latest/fusioncharts.js"></script>

    <script type="text/javascript" src="https://cdn.fusioncharts.com/fusioncharts/latest/themes/fusioncharts.theme.fusion.js"></script>
    <script type="text/javascript">
        const categories ={!!$categories!!};
        const dataset ={!!$dataset!!};
        //STEP 3 - Chart Configurations
        const chartConfig = {
        type: 'stackedcolumn2d',
        renderAt: 'chart-container',
        width: '100%',
        height: '400',
        dataFormat: 'json',
        dataSource: {
            // Chart Configuration
            "chart": {
                "caption": "Monthly Supplier Performance",
                "subCaption": "Completion Date vs Actual",
                "xAxisName": "Months",
                "yAxisName": "Total Deliveries",
                "numberSuffix": "",
                "theme": "fusion",
                },
            // Chart Data
            "categories": categories,
            "dataset": dataset
            }
        };
        FusionCharts.ready(function(){
        var fusioncharts = new FusionCharts(chartConfig);
        fusioncharts.render();
        });
    </script>
@endsection

@section('content')
<div class="page-content">
    <!-- BEGIN BREADCRUMBS -->
    <div class="breadcrumbs">
        <h1>Logistics Dashboard</h1>
    </div>
    <!-- END BREADCRUMBS -->

    <div class="row">
        <div class="col-md-12">
            <div id="chart-container"></div>
        </div>
        <div class="col-md-12">
           {!!$raw!!}
        </div>
    </div>

    
 
</div>
@endsection

@section('pagejs')
  

  
@endsection
