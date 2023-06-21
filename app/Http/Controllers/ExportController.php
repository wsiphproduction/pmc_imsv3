<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Excel;

use App\Export\OverdueDeliveries;
use App\Export\OverduePayables;
use App\Export\DeliveryStatus;
use App\Export\OverduePO;
use App\Export\POStatus;



class ExportController extends Controller {

    ## EXPORT
    public function delivery_status(Request $req) {

        return Excel::download(new DeliveryStatus($req), 'Delivery Status .xlsx');

    }

    public function overdue_payables(Request $req) {

        return Excel::download(new OverduePayables($req), 'Overdue Payments.xlsx');  

    }

    public function po_status(Request $req) {

        return Excel::download(new POStatus($req), 'PO Status.xlsx');   

    }


    public function overdue_deliveries(Request $req) {

        return Excel::download(new OverdueDeliveries($req), 'Overdue Deliveries.xlsx');  

    }

    public function overdue_po(Request $req) {

        return Excel::download(new OverduePO($req), 'Overdue Completion.xlsx');  

    }

}