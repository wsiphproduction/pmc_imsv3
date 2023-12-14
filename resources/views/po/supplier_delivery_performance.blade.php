

@extends('layouts.app')

@section('pagecss')
<link href="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
<link href="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
<link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel ="stylesheet">
<link href="{{env('APP_URL')}}/assets/css/style.css" rel="stylesheet" type="text/css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.min.js"></script>
<script src="https://emn178.github.io/chartjs-plugin-labels/src/chartjs-plugin-labels.js"></script>
@endsection

@section('content')

<div class="page-content">

<form class="form" action="{{ route('ims.supplier_delivery_performance') }}" method="get">
  <div class="form-group d-flex justify-content-between">
    <label class="mr-2" for="supplierInput">Select Supplier:</label>
    <div class="input-group">
        <input list="suppliers" class="form-control" id="supplierInput" value="{{request()->supplierInput}}" name="supplierInput">
        <datalist id="suppliers">
            @foreach ( $supplierNames as $supplierName )
                <option value="{{ $supplierName->supplier_name }}">
            @endforeach
        </datalist>
    </div>
    <button type="submit" class="btn btn-primary" id="filterSuppliers" style="margin-top: -55px; margin-left: 220px;">Filter</button>
  </div>
</form>

<div class="row">
  <div class="col-md-8">
    <table class="table chartdata {{$show ? '':'invisible'}}" id="purchaseOrderTable">
      <thead>
        <tr>
          <th scope="col">Name</th>
          <th scope="col">PO Number</th>
          <th scope="col">Expected Delivery Date</th>
          <th scope="col">Actual Delivery Date</th>
          <th scope="col">Late Days</th>
          <th scope="col">Remarks</th>
        </tr>
      </thead>
      <tbody>
          @foreach ($purchaseOrders as $purchaseOrder)
              <tr>
                  <td>{{ $purchaseOrder->supplier_name }}</td>
                  <td>{{ $purchaseOrder->poNumber }}</td>
                  <td>{{ $purchaseOrder->expectedDeliveryDate }}</td>
                  <td>{{ $purchaseOrder->actualDeliveryDate }}</td>
                  <td>{{ $purchaseOrder->late_days }}</td>
                  <td>
                    @if ($purchaseOrder->late_days <= 0)
                        <h5 class="pass">Passed</h5>
                    @else
                        <h5 class="fail">Failed</h5>
                    @endif
                </td>
              </tr>
          @endforeach
      </tbody>
    </table>
  </div>
  <div class="col-md-4 chartdata" style="height: 100%; margin-top: -96px;">
    <div class="chart {{$show ? '':'invisible'}}">
      <canvas id="myChart" width="300" height="300"></canvas>
    </div>
  </div>
</div>
      
    <div class="paginate {{$show ? '':'invisible'}}">
      {{ $purchaseOrders->links() }}
    </div> 
</div>

@include('modal.payments')

@endsection

@section('pagejs')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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
   
  const ctx = document.getElementById('myChart');
  const failedColor = 'red';
  const passedColor = 'green';

  const data = [{{ $failedCount }}, {{ $passedCount }}];

  new Chart(ctx, {
  type: 'pie',
  data: {
    labels: ['Failed', 'Passed'],
    datasets: [{
      data: data,
      backgroundColor: [failedColor, passedColor],
      borderWidth: 1
    }]
  },
  options: {
    plugins: {
      labels: {
        render: 'percentage',
        precision: 1,
        position: 'default',
        overlap: false,
        fontColor: '#fff',
        fontStyle: 'bold',
        fontSize: 12
      }
    },
    tooltips: {
      enabled: true // Disable tooltips
    },
    hover: {
      mode: null // Disable hover effect
    }
  }
});

</script>


@endsection




