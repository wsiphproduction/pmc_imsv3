@extends('layouts.app')

    
@section('pagecss')
    <script type="text/javascript" src="https://cdn.fusioncharts.com/fusioncharts/latest/fusioncharts.js"></script>

    <script type="text/javascript" src="https://cdn.fusioncharts.com/fusioncharts/latest/themes/fusioncharts.theme.fusion.js"></script>
    <script type="text/javascript">
        const chartData = [{
            "label": "Passed",
            "value": "{{$data['com_pass']}}"
        }, {
            "label": "Failed",
            "value": "{{$data['com_fail']}}"
        }];

        //STEP 3 - Chart Configurations
        const chartConfig = {
        type: 'pie3d',
        renderAt: 'chart-container',
        width: '100%',
        height: '400',
        dataFormat: 'json',
        dataSource: {
            // Chart Configuration
            "chart": {
                "caption": "Supplier Performance",
                "subCaption": "Completion Date vs Actual",
                "xAxisName": "Country",
                "yAxisName": "Reserves (MMbbl)",
                "numberSuffix": "",
                "theme": "fusion",
                },
            // Chart Data
            "data": chartData
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
        <div class="col-md-4">
            <input type="date" name="start" id="start" class="form-control input-sm" value="{{$data['start']}}">
        </div>
        <div class="col-md-4">
            <input type="date" name="end" id="end" class="form-control input-sm" value="{{$data['end']}}">
        </div>
        <div class="col-md-4">
            <input type="submit" class="btn btn-info" value="Submit">
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div id="chart-container"></div>
        </div>
        <div class="col-md-12">
           {!!$data['raw']!!}
        </div>
    </div>

    
 
</div>
@endsection

@section('pagejs')
  

  
@endsection
