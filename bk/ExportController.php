<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Excel 
use Excel;

use App\Exports\ExportPayments;
use App\Exports\ExportSupplier;
use App\Exports\ExportUpcomingDeliveries;
use App\Exports\ExportInProgress;
use App\Exports\ExportInTransit;
use App\Exports\ExportCustomClearing;
use App\Exports\ExportForPickUp;
use App\Exports\ExportDelivered;
use App\Exports\ExportOpen;
use App\Exports\ExportUndeliveredPO;
use App\Exports\ExportDeliveryAging;
use App\Exports\ExportPOStatus;
use App\Exports\ExportWaybill;

use App\Exports\ExportOverduePayments;
use App\Exports\ExportOverduePaymentsWithSupplier;
use App\Exports\ExportOverduePaymentsSupplierOnly;
use App\Exports\ExportOverdueExcel;

use App\Exports\ExportUnpaidPayments;
use App\Exports\ExportUnpaidPaymentsWithSupplier;
use App\Exports\ExportUnpaidPaymentsSupplierOnly;
use App\Exports\ExportUnpaidExcel;

use App\Exports\ExportOverduePO;
use App\Exports\ExportOverduePOWithSupplier;
use App\Exports\ExportOverduePOExcel;
use App\Exports\ExportOverduePOSupplierOnly;

use App\Exports\EXportCompletionDate;

use App\Exports\ExportOverdueDeliveryExcel;
use App\Exports\ExportOverdueSupplierDelivery;
use App\Exports\ExportOverdueSupplierDeliveryDate;



use App\Export\DeliveryStatus;


class ExportController extends Controller {

    ## EXPORT
    public function delivery_status(Request $req) {
        return Excel::download(new DeliveryStatus($req), 'Delivery Status .xlsx');
    }






















    public function exportPayments(Request $req) {
        $today = Carbon::today();
        return Excel::download(new ExportPayments($req), 'Weekly Payments '.$today.'.xlsx');
    }


    public function exportSupplier(Request $req) {
        $today = Carbon::today();
        return Excel::download(new ExportSupplier($req), 'Payments per Supplier '.$today.'.xlsx');
    }

    public function exportUpcomingDeliveries(Request $req) {
        $today = Carbon::today();
        return Excel::download(new ExportUpcomingDeliveries($req), 'Upcoming Deliveries '.$today.'.xlsx');
    }

    public function inProgress(Request $req) {
        $today = Carbon::today();

        return Excel::download(new ExportInProgress($req), 'In-Progress Deliveries '.$today.'.xlsx');   
    }

    public function inTransit(Request $req) {
        $today = Carbon::today();

        return Excel::download(new ExportInTransit($req), 'In-Transit Deliveries '.$today.'.xlsx');   
    }

    public function customClearing(Request $req) {
        $today = Carbon::today();

        return Excel::download(new ExportCustomClearing($req), 'Custom Clearing Deliveries '.$today.'.xlsx');   
    }

    public function forPickUp(Request $req) {
        $today = Carbon::today();

        return Excel::download(new ExportForPickUp($req), 'For Pick-Up Deliveries '.$today.'.xlsx');   
    }

    public function exportDelivered(Request $req) {
        $today = Carbon::today();

        return Excel::download(new ExportDelivered($req), 'Delivered '.$today.'.xlsx');   
    }

    public function exportOpen(Request $req) {
        $today = Carbon::today();

        return Excel::download(new ExportOpen($req), 'Open PO '.$today.'.xlsx');   
    }

    public function undeliveredPO(Request $req) {
        $today = Carbon::today();

        return Excel::download(new ExportUndeliveredPO($req), 'Undelivered PO '.$today.'.xlsx');   
    }

    public function deliveryAging(Request $req) {
        $today = Carbon::today();

        return Excel::download(new ExportDeliveryAging($req), 'Delivery Aging '.$today.'.xlsx');   
    }

    public function poStatus(Request $req) {
        $today = Carbon::today();

        return Excel::download(new ExportPOStatus($req), 'PO '.$today.'.xlsx');   
    }

    public function waybills(Request $req) {
        $today = Carbon::today();

        return Excel::download(new ExportWaybill($req), 'Waybill '.$today.'.xlsx');   
    }



    public function overduePayments(Request $req) {
        $today = Carbon::today();

        return Excel::download(new ExportOverduePayments($req), 'Overdue Payments '.$today.'.xlsx');   
    }
    
    public function overduePaymentsWithSupplier(Request $req) {
        $today = Carbon::today();

        return Excel::download(new ExportOverduePaymentsWithSupplier($req), 'Overdue Payments by Supplier '.$today.'.xlsx');   
    }

    public function overduePaymentsSupplier(Request $req) {
        $today = Carbon::today();

        return Excel::download(new ExportOverduePaymentsSupplierOnly($req), 'Overdue Payments by Supplier '.$today.'.xlsx');   
    }

    public function excelOverdue(Request $req) {
        $today = Carbon::today();

        return Excel::download(new ExportOverdueExcel($req), 'Overdue Payables '.$today.'.xlsx');   
    }



    public function unpaidPayments(Request $req) {
        $today = Carbon::today();

        return Excel::download(new ExportUnpaidPayments($req), 'Unpaid Payments '.$today.'.xlsx');   
    }

    public function unpaidPaymentsWithSupplier(Request $req) {
        $today = Carbon::today();

        return Excel::download(new ExportUnpaidPaymentsWithSupplier($req), 'Unpaid Payments by Supplier '.$today.'.xlsx');   
    }

    public function unpaidPaymentsSupplier(Request $req) {
        $today = Carbon::today();

        return Excel::download(new ExportUnpaidPaymentsSupplierOnly($req), 'Unpaid Payments by Supplier '.$today.'.xlsx');   
    }

    public function excelUnpaid(Request $req) {
        $today = Carbon::today();

        return Excel::download(new ExportUnpaidExcel($req), 'Unpaid Payments '.$today.'.xlsx');   
    }

    public function excelOverduePO(Request $req) {
        $today = Carbon::today();

        return Excel::download(new ExportOverduePOExcel($req), 'Overdue PO '.$today.'.xlsx');   
    }

    public function overduePO(Request $req) {
        $today = Carbon::today();

        return Excel::download(new ExportOverduePO($req), 'Overdue PO '.$today.'.xlsx');   
    }

    public function overduePOWithSupplier(Request $req) {
        $today = Carbon::today();

        return Excel::download(new ExportOverduePOWithSupplier($req), 'Overdue PO by Supplier '.$today.'.xlsx');   
    }

    public function overduePOSupplier(Request $req) {
        $today = Carbon::today();

        return Excel::download(new ExportOverduePOSupplierOnly($req), 'Overdue PO by Supplier '.$today.'.xlsx');   
    }

    public function completionDate(Request $req) {
        $today = Carbon::today();

        return Excel::download(new EXportCompletionDate($req), 'Completion Date '.$today.'.xlsx');   
    }

    public function excelOverdueDelivery(Request $req) {
        $today = Carbon::today();

        return Excel::download(new ExportOverdueDeliveryExcel($req), 'Overdue Delivery '.$today.'.xlsx');   
    }

    public function excelOverdueSupplierDelivery(Request $req) {
        $today = Carbon::today();

        return Excel::download(new ExportOverdueSupplierDelivery($req), 'Overdue Supplier Delivery '.$today.'.xlsx');   
    }

    public function excelOverdueSupplierDeliveryDate(Request $req) {
        $today = Carbon::today();

        return Excel::download(new ExportOverdueSupplierDeliveryDate($req), 'Overdue Supplier Delivery Date'.$today.'.xlsx');   
    }

    

    

    
    


}