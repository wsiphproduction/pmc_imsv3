

@extends('layouts.app')

@section('pagecss')
<link href="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
<link href="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel ="stylesheet">
<style>
    .b-price-plan__item{
        padding: 30px 30px; 
    }
    .b-price-plan__cost{    
        font-size: 20px;
        font-weight: 600;
        position: relative;
        z-index: 99;
        text-align: center; 
        background: rgba(125,138,164,.1);
    }
    .cost-title{
        font-size: 45px;
        line-height: 1;
        font-weight: 700;   
        color: rgba(125,138,164,1);
    }

    .best-plan__title{
        font-size: 18px;
        margin-bottom: 15px;
        margin-top:50px;
        font-weight: 800;
        color:#3c2f17;
    }

    .best-plan__month{
        font-size: 18px;
        margin-bottom: 15px;
        margin-top:30px;
        font-weight: 800;
        color:white;
    }
    .date-container {
      display: inline-block;
      margin-right: 20px; /* Adjust as needed for spacing */
    }
    /* .ui-datepicker{
        position: absolute;
         top: 192px;
         left: 125.812px;
         z-index: 1;
        display: block;
    } */

</style>
@endsection

@section('content')

<div class="page-content">

        <div class="contain">
          <form method="GET" action="{{ route('ims.total_pending') }}">
            <div class="date-container">
                Start Date: <input id="datepicker" value="{{ $start_date }}" name="start_date" width="220" style="border-radius: 4px;
                height: 34px;" />
            </div>

            <div class="date-container">
                End Date: <input id="datepickertwo" value="{{ $end_date }}" name="end_date" width="220" style="border-radius: 4px;
                height: 34px;" />
            </div>
           <div class="btn">
            <button type="submit" class="btn btn-primary">Filter</button>
           </div>
        </form>
        </div>
  <div>
    <canvas id="chart" style="width:100%; margin-top: 47px;"></canvas>
  </div>
  <br>
   
    <h4>Daily Pending Records</h4>


    <table class="table">
        <thead>
        <tr>
            <th scope="col">Date</th>
            <th scope="col">Overdue Payable</th>
            <th scope="col">Overdue Completion</th>
            <th scope="col">Total Open PO</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            @foreach($records as $record)
                <tr>
                    <td>{{ $record->date }}</td>
                    <td>{{ $record->overdue_payable }}</td>
                    <td>{{ $record->overdue_completion }}</td>
                    <td>{{ $record->total_open_po }}</td>
                </tr>
            @endforeach
        </tr>
        </tbody>
    </table>

    {{ $paginates->links() }}
</div>


@include('modal.payments')

@endsection

@section('pagejs')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src = "https://code.jquery.com/jquery-1.10.2.js"></script>
<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/morris/morris.min.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/morris/raphael-min.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/amcharts/amcharts/amcharts.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/amcharts/amcharts/serial.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/amcharts/amcharts/pie.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/amcharts/amcharts/radar.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/amcharts/amcharts/themes/light.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/amcharts/amcharts/themes/patterns.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/amcharts/amcharts/themes/chalk.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/amcharts/ammap/ammap.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/amcharts/ammap/maps/js/worldLow.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/amcharts/amstockcharts/amstock.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/pages/scripts/dashboard.min.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>


<script>
    const ctx = document.getElementById('chart');

    new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($data->pluck('date')) !!},
        datasets: [{
            label: 'Overdue Payable',
            data: {!! json_encode($data->pluck('overdue_payable')) !!},
            borderWidth: 1
        }, {
            label: 'Overdue Completion',
            data: {!! json_encode($data->pluck('overdue_completion')) !!},
            borderWidth: 1
        }, {
            label: 'Total Open PO',
            data: {!! json_encode($data->pluck('total_open_po')) !!},
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

$('#datepicker').datepicker({ uiLibrary: 'bootstrap', modal: true, header: true, footer: true });
$('#datepickertwo').datepicker({ uiLibrary: 'bootstrap', modal: true, header: true, footer: true });
  </script>
 

@endsection




