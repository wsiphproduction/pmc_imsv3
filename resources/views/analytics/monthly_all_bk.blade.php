@extends('layouts.app')

    
@section('pagecss')
    <script type="text/javascript" src="https://cdn.fusioncharts.com/fusioncharts/latest/fusioncharts.js"></script>

    <script type="text/javascript" src="https://cdn.fusioncharts.com/fusioncharts/latest/themes/fusioncharts.theme.fusion.js"></script>
    <script type="text/javascript">
        @for($i = 1; $i <= $x; $i++)
            const chartData{{$i}} = [{
                "label": "Passed",
                "value": "{{$data['com_pass'.$i]}}"
            }, {
                "label": "Failed",
                "value": "{{$data['com_fail'.$i]}}"
            }];

            //STEP 3 - Chart Configurations
            const chartConfig{{$i}} = {
            type: 'pie2d',
            renderAt: 'chart-container{{$i}}',
            width: '100%',
            height: '400',
            dataFormat: 'json',
            dataSource: {
                // Chart Configuration
                "chart": {
                    "caption": "Monthly Supplier Performance",
                    "subCaption": "Completion Date vs Actual {{$data['year'.$i]}} - {{$data['month'.$i]}}",
                    "numberSuffix": "",
                    "theme": "fusion",
                    },
                // Chart Data
                "data": chartData{{$i}}
                }
            };
            FusionCharts.ready(function(){
            var fusioncharts{{$i}} = new FusionCharts(chartConfig{{$i}});
            fusioncharts{{$i}}.render();
            });
        @endfor
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
        @for($i = 1; $i <= $x; $i++)
            <div class="col-md-4">                
                <div id="chart-container{{$i}}"></div>                
            </div>
        @endfor
        
    </div>

    
 
</div>
@endsection

@section('pagejs')
  

  
@endsection
