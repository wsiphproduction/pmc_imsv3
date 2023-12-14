<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;
use File;
use DB;
use Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use Illuminate\Pagination\Paginator;
use App\Services\RoleRightService;

//Models
use App\PO;
use App\supplier;
use App\PaymentSchedule;
use App\drr;
use App\logistics;
use App\remarks;
use App\AuditLogs;
use App\DailyPending;

class ProcessesController extends Controller
{
    public function __construct(
        RoleRightService $roleRightService
    ) {
        $this->roleRightService = $roleRightService;
    }
    // MAIN PO

    public function dashboard()
    {
       
        $rolesPermissions = $this->roleRightService->hasPermissions("Dashboard");
    
        if (!$rolesPermissions['view']) {
            abort(401);
        }

        $create = $rolesPermissions['create'];
        $edit = $rolesPermissions['edit'];
        $delete = $rolesPermissions['delete'];
        $print = $rolesPermissions['print'];
        $upload = $rolesPermissions['upload'];

        $monthly_completion = PO::whereBetween('expectedCompletionDate', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->orderBy('expectedCompletionDate', 'asc')
            ->get();

        // display the total record to dashboard from each table
        $overdue_payables = PaymentSchedule::totalOverduePayables();
        $overdue_completion = PO::totalOverduePO_new();
        $total_open_po = PO::totalOpenPO();

        $date_exists = DailyPending::where('date', Carbon::now())->first();

        if($date_exists == null){
            DailyPending::create([
                'date' => Carbon::now(),
                'overdue_payable' => $overdue_payables,
                'overdue_completion' => $overdue_completion,
                'total_open_po' => $total_open_po,
                'created_at' => Carbon::now()->format('Y-m-d H:i'),  
                'updated_at' => Carbon::now()->format('Y-m-d H:i')
            ]);
        }
        // $records = DailyPending::orderBy('date', 'asc');
       

        $activities = AuditLogs::orderBy('id', 'desc')->get();
        return view('po.dashboard', compact(
            'monthly_completion',
            'activities',
            'create',
            'edit',
            'delete',
            'print',
            'upload'
        ));
    }

 
    public function showRecords(Request $request)
    {

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = DailyPending::query();

             // if the date column is bigger than or equal for startdate
        if ($startDate) {
            $query->whereDate('date', '>=', $startDate);
        }
            // if the date column is lower than or equal for startdate
        if ($endDate) {
            $query->whereDate('date', '<=', $endDate);
        }

        $query->orderBy('date', 'asc');
        $paginates = DailyPending::paginate(5);
        $records = $query->get();

        $show = true;
       

        return view('po.showrecord', [
            'records' => $records,
            'data' => $query,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'paginates' => $paginates,
            'show' => $show,
        ]);
    }

    public function performance(Request $request)
    {
       
        $show = false;

        $query = DB::table('po')
        ->leftJoin('supplier as s', 's.id', '=', 'po.supplier')
        ->select(
            's.name as supplier_name',
            'poNumber',
            'expectedDeliveryDate',
            'actualDeliveryDate',
            DB::raw('DATEDIFF(day, expectedDeliveryDate, actualDeliveryDate) as late_days')
        )
        ->whereNotNull('actualDeliveryDate')
        ->orderBy('actualDeliveryDate', 'desc'); 
    
    
        if ($request->filled('supplierInput')) {
            $query->where('s.name', $request->supplierInput);
            $show = true;
        }
        

        $res = $query->get();

        $failedCount = $res->where('late_days', '>', 0)->count();
       
        $passedCount = $res->where('late_days', '<', 0)->count();


        $purchaseOrders = $query->paginate(5)->appends($request->except('page'));
        $lateDaysData = $purchaseOrders->pluck('late_days')->toArray();
       
        
        // Fetch distinct supplier names
        $supplierNames = DB::table('supplier')
            ->leftJoin('po', 'po.supplier', '=', 'supplier.id')
            ->select('supplier.name as supplier_name')
            ->whereNotNull('po.actualDeliveryDate')
            ->distinct()
            ->get();

        // dd($supplierNames);


        return view('po.supplier_delivery_performance', compact(
            'purchaseOrders', 
            'supplierNames', 
            'request', 
            'lateDaysData', 
            'failedCount', 
            'passedCount',
            'show'
        ));
    }
    
    public function dashboard2()
    {
       
        $monthly_completion = PO::whereBetween('expectedCompletionDate', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->orderBy('expectedCompletionDate', 'asc')
            ->get();
    
        return view('po.dashboard2', compact('monthly_completion'));
    }

    public function search()
    {
        $params = Input::all();

        return $this->po_list($params);
    }

    public function po_list($param = null)
    {
        $rolesPermissions = $this->roleRightService->hasPermissions("PO List");

        if (!$rolesPermissions['view']) {
            abort(401);
        }

        $create = $rolesPermissions['create'];
        $edit = $rolesPermissions['edit'];
        $delete = $rolesPermissions['delete'];
        $print = $rolesPermissions['print'];
        $upload = $rolesPermissions['upload'];

        $collection = PO::whereNotNull('id');

        $pageLimit = 10;

        if (isset($param)) {

            if (isset($param['poNumber'])) {
                $collection->where('poNumber', $param['poNumber']);
            }

            if (isset($param['supplier'])) {
                $collection->where('supplier', $param['supplier']);
            }
        } else {

            $param = [];
            $collection->where('status', 'OPEN')->orderBy('id', 'desc');
        }

        $collection = $collection->paginate($pageLimit);
        $suppliers = supplier::orderBy('name')->get();

        return view('po.list', compact(
            'collection',
            'param',
            'suppliers',
            'create',
            'edit',
            'delete',
            'print',
            'upload'
        ));
    }


    public function details($id)
    {
     
        $po = PO::where('id', $id)->first();
      
        $shipments   = logistics::where('poId', $id)->orderBy('expectedDeliveryDate', 'asc')->get();
        $payments    = PaymentSchedule::where('poId', $id)->orderBy('paymentDate', 'asc')->get();
        $deliveries  = drr::where('poNumber', $id)->orderBy('id', 'desc')->get();
       
        $total_payment    = count($payments);
        $total_shipment   = count($shipments);
        $total_deliveries = count($deliveries);
        
        // Files
        //$old_files = file_get_contents("http://172.16.20.27/ims_v3/migration/old_api.php?d=po&id=".$id);
        $old_files = '';


        return view('po.details', compact('po', 'shipments', 'payments', 'deliveries', 'total_payment', 'total_shipment', 'total_deliveries', 'old_files'));
    }

    public function manual_delete_po(Request $req)
    {
        $data = PO::find($req->pid)->delete();

        if ($data) {
            PaymentSchedule::where('poId', $req->pid)->delete();
            logistics::where('poId', $req->pid)->delete();
            drr::where('poNumber', $req->pid)->delete();
        }

        $notification = array(
            'message' => 'PO details, payment schedule, shipment schedule and receiving report hase been deleted.',
            'alert-type' => 'success'
        );

        return back()->with('notification', $notification);
    }

    public function manual_close_po(Request $req)
    {
        $data = PO::find($req->cid);
        $data->status = 'CLOSED';
        $data->closedDate = Carbon::now();
        $data->closedBy = Auth::user()->domainAccount;
        $data->save();

        $notification = array(
            'message' => 'PO closed successfully.',
            'alert-type' => 'success'
        );

        return back()->with('notification', $notification);
    }

    // public function manual_open_po(Request $req)
    // {   
    //     PO::find($req->pid)->update([
    //         'status' => 'OPEN'
    //     ]);

    //     $notification = array(
    //         'message' => 'PO open successfully.', 
    //         'alert-type' => 'success'
    //     );

    //     return back()->with('notification',$notification);
    // }


    ############## FILES FUNCTIONS ##############
    public function preview_file(Request $req)
    {
        $today = date('Y-m-d', strtotime(Carbon::today()));

        if (!Storage::exists('/public/' . $today)) {

            Storage::makeDirectory('/public/' . $today, 0775, true);
        }

        $dir = '\\\\ftp\\FTP\\APP_UPLOADED_FILES\\ims\\' . $req->po . '\\po\\' . $req->fileName;

        
        // $dir = 'D:\\uploaded files\\ims\\' . $req->po . '\\po\\' . $req->fileName;
        $dst = storage_path() . '/app/public/' . $today . '/' . $req->fileName;
        copy($dir, $dst);
    }

    public function preview_drr_file(Request $req)
    {
        $today = date('Y-m-d', strtotime(Carbon::today()));

        if (!Storage::exists('/public/' . $today)) {

            Storage::makeDirectory('/public/' . $today, 0775, true);
        }

        $dir = '\\\\ftp\\FTP\\APP_UPLOADED_FILES\\ims\\' . $req->po . '\\mcd\\' . $req->did . '\\' . $req->fileName;
        $dst = storage_path() . '/app/public/' . $today . '/' . $req->fileName;

        copy($dir, $dst);
    }


    public function upload(Request $req)
    {
        if (($req->has('uploadFile'))) {
            $files = $req->file('uploadFile');
             $file_path = '\\\\ftp\FTP\APP_UPLOADED_FILES\ims\\' . $req->po;
            // $file_path = 'D:\\uploaded files\\ims\\' . $req->po;

            if (!file_exists($file_path)) {

                mkdir($file_path, 0775, true);
                mkdir($file_path . '\\po');

                $destinationPath = '\\\\ftp\FTP\APP_UPLOADED_FILES\ims\\' . $req->po . '\\po';
                // $destinationPath = 'D:\\uploaded files\\ims\\' . $req->po . '\\po';

            } else {
                $destinationPath = '\\\\ftp\FTP\APP_UPLOADED_FILES\ims\\' . $req->po . '\\po';
                // $destinationPath = 'D:\\uploaded files\\ims\\' . $req->po . '\\po';

            }

            foreach ($files as $file) {
                $file->move($destinationPath, $file->getClientOriginalName());
            }
        }

        $notification = array(
            'message' => 'Files has been uploaded.',
            'alert-type' => 'success'
        );

        return back()->with('notification', $notification);
    }
    ############## FILES FUNCTIONS ##############

}
