

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
      margin-right: 20px; 
    }
   /*the container must be positioned relative:*/
.custom-select {
  position: relative;
  font-family: Arial;
}

.custom-select select {
  display: none; /*hide original SELECT element:*/
}

.select-selected {
  background-color: DodgerBlue;
}

/*style the arrow inside the select element:*/
.select-selected:after {
  position: absolute;
  content: "";
  top: 17px;
  right: 10px;
  width: 0;
  height: 0;
  border: 6px solid transparent;
  border-color: #fff transparent transparent transparent;
}

/*point the arrow upwards when the select box is open (active):*/
.select-selected.select-arrow-active:after {
  border-color: transparent transparent #fff transparent;
  top: 7px;
}

/*style the items (options), including the selected item:*/
.select-items div,.select-selected {
  color: #ffffff;
  padding: 8px 16px;
  border: 1px solid transparent;
  border-color: transparent transparent rgba(0, 0, 0, 0.1) transparent;
  cursor: pointer;
  user-select: none;
}

/*style items (options):*/
.select-items {
  position: absolute;
  background-color: DodgerBlue;
  top: 100%;
  left: 0;
  right: 0;
  z-index: 99;
}

/*hide the items when the select box is closed:*/
.select-hide {
  display: none;
}

.select-items div:hover, .same-as-selected {
  background-color: rgba(0, 0, 0, 0.1);
}

</style>
@endsection

@section('content')

<div class="page-content">
  <form class="form" action="{{ route('ims.supplier_delivery_performance') }}" method="get">
    <div class="form-group d-flex justify-content-between">
      <label class="mr-2" for="supplierInput">Select Supplier:</label>
      <div class="input-group">
          <input list="suppliers" class="form-control" id="supplierInput" value="{{ $request->input('supplierInput') }}" name="supplierInput">
          <datalist id="suppliers">
              @foreach ( $supplierNames as $supplierName )
                  <option value="{{ $supplierName->supplier_name }}">
              @endforeach
          </datalist>
      </div>
      <button type="submit" class="btn btn-primary" id="filterSuppliers" style="margin-top: -55px; margin-left: 220px;">Filter</button>
  </div>
</form>

    <br>
      <br>
      
    <table class="table" id="purchaseOrderTable">
        <thead>
          <tr>
            <th scope="col">Name</th>
            <th scope="col">PO Number</th>
            <th scope="col">Expected Delivery Date</th>
            <th scope="col">Actual Delivery Date</th>
            <th scope="col">Late Days</th>
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
                </tr>
            @endforeach
        </tbody>
      </table>
      {{ $purchaseOrders->links() }}


</div>


@include('modal.payments')

@endsection

@section('pagejs')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src = "https://code.jquery.com/jquery-1.10.2.js"></script>
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
    var x, i, j, l, ll, selElmnt, a, b, c;
/*look for any elements with the class "custom-select":*/
x = document.getElementsByClassName("custom-select");
l = x.length;
for (i = 0; i < l; i++) {
  selElmnt = x[i].getElementsByTagName("select")[0];
  ll = selElmnt.length;
  /*for each element, create a new DIV that will act as the selected item:*/
  a = document.createElement("DIV");
  a.setAttribute("class", "select-selected");
  a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
  x[i].appendChild(a);
  /*for each element, create a new DIV that will contain the option list:*/
  b = document.createElement("DIV");
  b.setAttribute("class", "select-items select-hide");
  for (j = 1; j < ll; j++) {
    /*for each option in the original select element,
    create a new DIV that will act as an option item:*/
    c = document.createElement("DIV");
    c.innerHTML = selElmnt.options[j].innerHTML;
    c.addEventListener("click", function(e) {
        /*when an item is clicked, update the original select box,
        and the selected item:*/
        var y, i, k, s, h, sl, yl;
        s = this.parentNode.parentNode.getElementsByTagName("select")[0];
        sl = s.length;
        h = this.parentNode.previousSibling;
        for (i = 0; i < sl; i++) {
          if (s.options[i].innerHTML == this.innerHTML) {
            s.selectedIndex = i;
            h.innerHTML = this.innerHTML;
            y = this.parentNode.getElementsByClassName("same-as-selected");
            yl = y.length;
            for (k = 0; k < yl; k++) {
              y[k].removeAttribute("class");
            }
            this.setAttribute("class", "same-as-selected");
            break;
          }
        }
        h.click();
    });
    b.appendChild(c);
  }
  x[i].appendChild(b);
  a.addEventListener("click", function(e) {
      /*when the select box is clicked, close any other select boxes,
      and open/close the current select box:*/
      e.stopPropagation();
      closeAllSelect(this);
      this.nextSibling.classList.toggle("select-hide");
      this.classList.toggle("select-arrow-active");
    });
}
function closeAllSelect(elmnt) {
  /*a function that will close all select boxes in the document,
  except the current select box:*/
  var x, y, i, xl, yl, arrNo = [];
  x = document.getElementsByClassName("select-items");
  y = document.getElementsByClassName("select-selected");
  xl = x.length;
  yl = y.length;
  for (i = 0; i < yl; i++) {
    if (elmnt == y[i]) {
      arrNo.push(i)
    } else {
      y[i].classList.remove("select-arrow-active");
    }
  }
  for (i = 0; i < xl; i++) {
    if (arrNo.indexOf(i)) {
      x[i].classList.add("select-hide");
    }
  }
}

$(document).ready(function () {
        $('#filterSuppliers').click(function () {
            var inputVal = $('#supplierInput').val().toLowerCase();
            
            // Hide all options
            $('#suppliers option').prop('hidden', true);

            // Show only options that match the input value
            $('#suppliers option').filter(function () {
                return $(this).val().toLowerCase() === inputVal;
            }).prop('hidden', false);
        });
    });

</script>


@endsection




