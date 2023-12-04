<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Auth;
use \Carbon\Carbon;
use App\Services\ReportService;
use App\Services\UserService;

use App\supplier;
use App\PaymentSchedule;
use App\logistics;
use App\PO;
use App\Log;
use \OwenIt\Auditing\Models\Audit;
use App\Services\RoleRightService;
use App\DailyPending;

class ReportsController extends Controller
{
    public function __construct(
        RoleRightService $roleRightService,
        ReportService $reportService,
        UserService $userService
    ) {
        $this->reportService = $reportService;
        $this->roleRightService = $roleRightService;
        $this->userService = $userService;
    }

    // Delivery Status
    public function filter_delivery_status(Request $request)
    {
        $params = Input::all();

        return $this->delivery_status($request,$params);
    }

    public function delivery_status(Request $request, $param = null)
    {
        $rolesPermissions = $this->roleRightService->hasPermissions("Delivery Status");

        if (!$rolesPermissions['view']) {
            abort(401);
        }
        $shipments = logistics::whereNotNull('id');
        $pageLimit = 10;

        if (isset($param)) {

            if (isset($param['status']) == 'Pending') {
                $sort = 'expectedDeliveryDate';
            }

            if (isset($param['status']) == 'In-Transit') {
                $sort = 'departure_dt';
            }

            if (isset($param['status']) == 'All') {
                $sort = 'id';
            }

            $shipments->orderBy($sort, 'asc');

            if (isset($param['status'])) {
                if ($param['status'] == 'All') {
                    $shipments->whereNotNull('id');
                } else {
                    $shipments->where('status', '=', "" . $param['status'] . "");
                }
            }

            if (isset($param['from'])) {
                $shipments->whereBetween('actualDeliveryDate', ["" . date('Y-m-d', strtotime($param['from'])) . "", "" . date('Y-m-d', strtotime($param['to'])) . ""]);
            }
        } else {
            $param = [];
            $shipments->where('status', 'Pending')->orderBy('expectedDeliveryDate', 'asc');
        }

        $shipments = $shipments->paginate($pageLimit);

        $saveLogs = $this->reportService->create("Delivery Status", $request);
        return view('reports.delivery_status', compact('shipments', 'param'));
    }
    //


    // PO per Status
    public function filter_po_status()
    {
        $params = Input::all();

        return $this->po_status($params);
    }

    public function po_status()
    {
        $rolesPermissions = $this->roleRightService->hasPermissions("PO per Status");

        if (!$rolesPermissions['view']) {
            abort(401);
        }
        $purchases = PO::whereNotNull('id');

        if (isset($_GET)) {

            $purchases->orderBy('expectedCompletionDate', 'asc');

            if (isset($_GET['status'])) {
                $purchases->where('status', '=', "" . $_GET['status'] . "");
            }

            if (isset($_GET['from'])) {
                $purchases->whereBetween('expectedCompletionDate', ["" . date('Y-m-d', strtotime($_GET['from'])) . "", "" . date('Y-m-d', strtotime($_GET['to'])) . ""]);
            }
        } else {
            $_GET = [];
            $purchases->where('status', 'OPEN')->orderBy('expectedCompletionDate', 'asc');
        }

        $purchases = $purchases->get();
        $param = $_GET;
       // $saveLogs = $this->reportService->create("PO per Status", $request);
        return view('reports.po-per-status', compact('purchases', 'param'));
    }
    //


    // Overdue Completion
    public function filter_overdue_completion()
    {
        $params = Input::all();

        return $this->overdue_completion($params);
    }

    public function overdue_completion(Request $request, $param = null)
    {
        $rolesPermissions = $this->roleRightService->hasPermissions("Overdue Completion");

        if (!$rolesPermissions['view']) {
            abort(401);
        }
        $collection = PO::where('status', 'OPEN');

        if (isset($param)) {

            if (isset($param['from'])) {
                $collection->whereBetween('expectedCompletionDate', ["" . date('Y-m-d', strtotime($param['from'])) . "", "" . date('Y-m-d', strtotime($param['to'])) . ""])
                    ->orderBy('expectedCompletionDate', 'asc');
            }
        } else {

            $param = [];
            $collection->where('expectedCompletionDate', '<', Carbon::today())->orderBy('expectedCompletionDate', 'desc');
        }

        $collection = $collection->get();


        $saveLogs = $this->reportService->create("Overdue Completion", $request);
        return view('reports.overdue_completion', compact('collection', 'param'));
    }
    //


    // Overdue Shipments
    public function filter_overdue_shipments()
    {
        $params = Input::all();

        return $this->overdue_shipments($params);
    }

    public function overdue_shipments(Request $request, $param = null)
    {
        $rolesPermissions = $this->roleRightService->hasPermissions("Overdue Deliveries");

        if (!$rolesPermissions['view']) {
            abort(401);
        }
        $collection = logistics::where('status', 'Delivered');

        $pageLimit = 10;

        if (isset($param)) {

            if (isset($param['type']) == 2) {
                $collection->whereRaw('actualDeliveryDate > expectedDeliveryDate');
            }

            if (isset($param['from'])) {
                $collection->whereRaw('actualDeliveryDate > expectedDeliveryDate')->whereBetween('actualDeliveryDate', ["" . $param['from'] . "", "" . $param['to'] . ""])
                    ->orderBy('actualDeliveryDate', 'asc');
            }
        } else {

            $param = [];
            $collection->whereRaw('actualDeliveryDate > expectedDeliveryDate')->whereBetween('actualDeliveryDate', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->orderBy('actualDeliveryDate', 'desc');
        }

        $collection = $collection->paginate($pageLimit);

        $saveLogs = $this->reportService->create("Overdue Deliveries", $request);
        return view('reports.overdue_shipments', compact('collection', 'param'));
    }
    //

    // Overdue Payables
    public function overdue_payables(Request $request)
    {
     
        $rolesPermissions = $this->roleRightService->hasPermissions("Overdue Payables Reports");

        if (!$rolesPermissions['view']) {
            abort(401);
        }
        $collection = PaymentSchedule::where('isPaid', 0)->whereDate('paymentDate', '<', Carbon::today())->orderBy('paymentDate', 'desc')->paginate(15);
        $saveLogs = $this->reportService->create("Overdue Payables", $request);

         $count = PaymentSchedule::where('isPaid', 0)
        ->where('paymentDate', '<', now())
        ->count();

      
        $totalRecord = new \App\DailyPending;
        $dailyPending->overdue_payable = $total;
        $totalRecord->save();

        return view('reports.overdue_payables', compact('collection'));
       
        //return view('reports.overdue_payables',compact('collection','param'));
 
    }

    public function errorLogs(Request $request)
    {
        $rolesPermissions = $this->roleRightService->hasPermissions("Error Logs");

        if (!$rolesPermissions['view']) {
            abort(401);
        }
        $dateFrom = now()->toDateString();
        $dateTo = now()->toDateString();
        if (isset($request->dateFrom)) {
            $dateFrom = $request->dateFrom;
        }
        if (isset($request->dateTo)) {
            $dateTo = $request->dateTo;
        }

        $error_list = Log::when(isset($dateTo), function ($q) use ($dateFrom, $dateTo) {
            $q->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
        })
            ->when(!isset($dateTo), function ($q) use ($dateFrom) {
                $q->whereDate('created_at', $dateFrom);
            })
            ->orderBy('created_at', 'desc')->get();
        $saveLogs = $this->reportService->create("Error Logs", $request);
        return view('reports.error', ['error_list' => $error_list]);
    }
    public function auditLogs(Request $request)
    {
        $rolesPermissions = $this->roleRightService->hasPermissions("Audit Logs");
        if (!$rolesPermissions['view']) {
            abort(401);
        }
        $dateFrom = now()->toDateString();
        $dateTo = now()->toDateString();
        $userid = 0;
        if (isset($request->dateFrom)) {
            $dateFrom = $request->dateFrom;
        }
        if (isset($request->dateTo)) {
            $dateTo = $request->dateTo;
        }
        if (isset($request->userid)) {
            $userid = $request->userid;
        }

        $users =  $this->userService->all()->where('isActive', 1)->where('domainAccount', '<>', '')->sortBy('domainAccount');

        $audits = Audit::when(isset($dateTo), function ($q) use ($dateFrom, $dateTo) {
            $q->whereBetween('created_at',  [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
        })
            ->when(!isset($dateTo), function ($q) use ($dateFrom) {
                $q->whereDate('created_at', $dateFrom);
            })
            ->when($userid != 0, function ($q) use ($userid) {
                $q->where('user_id', $userid);
            })
            ->orderBy('created_at', 'desc')->get();

        $saveLogs = $this->reportService->create("Audit Logs", $request);
        return view('reports.audits', [
            'audits' => $audits,
            'users' => $users
        ]);
    }
    //
}
