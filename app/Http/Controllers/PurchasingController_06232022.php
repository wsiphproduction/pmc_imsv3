<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use \Carbon\Carbon;
use Auth;
use DB;

// Models
use App\PO;
use App\supplier;
use App\PaymentSchedule;
use App\TempPaymentSchedule;
use App\AuditLogs;
use App\Services\RoleRightService;

class PurchasingController extends Controller
{
    // PURCHASING MODULE
    public function __construct(
        RoleRightService $roleRightService
    ) {
        $this->roleRightService = $roleRightService;
    }
    public function addPO()
    {
        $rolesPermissions = $this->roleRightService->hasPermissions("Create New PO");

        if (!$rolesPermissions['view']) {
            abort(401);
        }

        $create = $rolesPermissions['create'];
        $edit = $rolesPermissions['edit'];
        $delete = $rolesPermissions['delete'];
        $print = $rolesPermissions['print'];
        $upload = $rolesPermissions['upload'];

        $supplier = supplier::all();
        $origin = DB::table('country_origin')->get();

        return view('purchasing.add', compact(
            'supplier',
            'origin',
            'create',
            'edit',
            'delete',
            'print',
            'upload'
        ));
    }

    public function savePO(Request $req)
    {

        $data    = $req->all();
        $dates   = $data['date'];
        $amounts = $data['amount'];
        $ptypes  = $data['payment_type'];


        $add = PO::create([
            'poNumber'              => $req->po_no,
            'rq'                    => $req->rq,
            'orderDate'             => $req->dt_order,
            'supplier'              => $req->supplier,
            'itemCommodity'         => $req->item_commodity,
            'poAmount'              => $req->po_amount,
            'qty'                   => $req->po_qty,
            'amount'                => $req->po_amount,
            'currency'              => $req->currency,
            'terms'                 => $req->terms,
            'expectedCompletionDate' => $req->dt_completion,
            'expectedDeliveryDate'  => $req->date_needed,
            'status'                => 'OPEN',
            'addedBy'               => Auth::user()->domainAccount,
            'suppliers_lead_time'   => $req->lead_time,
            'origin'                => $req->origin,
            'destination_port'      => $req->dest_port,
            'incoterms'             => $req->incoterms,
            'paymentStatus'         => 'unpaid',
            'deliveryStatus'        => 'undelivered',
            'delivery_term'         => $req->delivery_term,
            'rq_date'               => $req->rq_date,
            'mrs_no'                => $req->mrs_no,
            'email_receivers'       => $req->email_receivers,
            'addedDate'             => Carbon::now()
        ]);

        if ($add) {

            $this->audit($add->id);

            if ($req->delivery_term != 'consignment items') {
                if ($req->terms != 'credit') {

                    foreach ($amounts as $key => $a) {
                        $temp_payments = new TempPaymentSchedule();
                        $temp_payments->poId = $add->id;
                        $temp_payments->paymentDate = $dates[$key];
                        $temp_payments->amount = $a;
                        $temp_payments->payment_type = $ptypes[$key];
                        $temp_payments->addedBy = Auth::user()->domainAccount;
                        $temp_payments->addedAt = Carbon::now();
                        $temp_payments->save();
                    }

                    foreach ($amounts as $key => $a) {
                        $act_payments = new PaymentSchedule();
                        $act_payments->poId = $add->id;
                        $act_payments->paymentDate = $dates[$key];
                        $act_payments->amount = $a;
                        $act_payments->isPaid = 0;
                        $act_payments->payment_type = $ptypes[$key];
                        $act_payments->addedBy = Auth::user()->domainAccount;
                        $act_payments->addedDate = Carbon::today();
                        $act_payments->save();
                    }
                }
            }

            $notification = array(
                'message' => 'Purchase Order has been created.',
                'alert-type' => 'success'
            );

            return redirect()->route('po.details', $add->id . '#files')->with('notification', $notification);
        }
    }


    public function audit($id)
    {
        AuditLogs::create([
            'ref_id' => $id,
            'poId' => $id,
            'action' => 'insert',
            'log_date' => Carbon::now(),
            'users' => Auth::user()->domainAccount,
            'log_desc' => 'has been created'
        ]);
    }

    public function editPO($id)
    {
        $po = PO::where('id', $id)->first();
        $supplier = supplier::all();
        $origin = DB::table('country_origin')->get();


        return view('purchasing.edit', compact('po', 'supplier', 'origin'));
    }

    public function updatePO(Request $req)
    {
        PO::where('id', $req->id)->update([
            'poNumber' => $req->po_no,
            'orderDate' => $req->dt_order,
            'rq' => $req->rq,
            'supplier' => $req->supplier,
            'suppliers_lead_time' => $req->lead_time,
            'currency' => $req->currency,
            'incoterms' => $req->incoterms,
            'origin' => $req->origin,
            'destination_port' => $req->dest_port,
            'expectedCompletionDate' => $req->dt_completion,
            'expectedDeliveryDate' => $req->date_needed,
            'itemCommodity' => $req->item_commodity,
            'amount' => $req->amount,
            'poAmount' => $req->amount,
            'qty' => $req->qty,
            'rq_date' => $req->rq_dt,
            'mrs_no' => $req->mrs,
            'email_receivers' => $req->email_receivers,
            'updatedBy' => Auth::user()->domainAccount,
            'updateDate' => Carbon::now()
        ]);

        $notification = array(
            'message' => 'PO details has been updated.',
            'alert-type' => 'success'
        );

        return back()->with('notification', $notification);
    }

    public function checkDuplicatePO($id)
    {
        $po = PO::where('poNumber', '=', $id)->first();
        if ($po === null) {
            return 'none';
        } else {
            return 'PO number already exist. Click <a href="/ims/po/details/' . $po->id . '">here</a> to view.';
        }
    }

    // Supplier
    public function search()
    {
        $params = Input::all();

        return $this->suppliers($params);
    }

    public function suppliers($param = null)
    {
        $rolesPermissions = $this->roleRightService->hasPermissions("Manage Suppliers");

        if (!$rolesPermissions['view']) {
            abort(401);
        }

        $create = $rolesPermissions['create'];
        $edit = $rolesPermissions['edit'];
        $delete = $rolesPermissions['delete'];
        $print = $rolesPermissions['print'];
        $upload = $rolesPermissions['upload'];

        $collection = supplier::whereNotNull('id');

        $pageLimit = 10;

        if (isset($param)) {

            if (isset($param['supplier'])) {
                $collection->where('name', 'like', "%" . $param['supplier'] . "%");
            }
        } else {

            $param = [];
            $collection->orderBy('id', 'desc');
        }

        $collection = $collection->paginate($pageLimit);

        return view('purchasing.supplier', compact(
            'collection',
            'param',
            'create',
            'edit',
            'delete',
            'print',
            'upload'
        ));
    }

    public function supplier_create()
    {
        return view('purchasing.supplier_create');
    }

    public function supplier_store(Request $req)
    {
        supplier::create([
            'Supplier_Code' => $req->code,
            'name' => $req->name,
            'address' => $req->address,
            'contact' => $req->contact,
            'LTO_validity' => $req->lto,
            'Contact_Person' => $req->contact_person
        ]);

        $notification = array(
            'message' => 'Supplier has been created.',
            'alert-type' => 'success'
        );

        return redirect()->route('supplier.index')->with('notification', $notification);
    }

    public function supplier_edit($id)
    {
        $supplier = supplier::where('id', $id)->first();

        return view('purchasing.supplier_edit', compact('supplier'));
    }

    public function supplier_update(Request $req)
    {
        supplier::where('id', $req->id)->update([
            'Supplier_Code' => $req->code,
            'name' => $req->name,
            'address' => $req->address,
            'contact' => $req->contact,
            'LTO_validity' => $req->lto,
            'Contact_Person' => $req->contact_person
        ]);

        $notification = array(
            'message' => 'Supplier details has been updated.',
            'alert-type' => 'success'
        );

        return redirect()->route('supplier.index')->with('notification', $notification);
    }

    public function kpi()
    {
        $rolesPermissions = $this->roleRightService->hasPermissions("Purchasing KPI");

        if (!$rolesPermissions['view']) {
            abort(401);
        }

        $create = $rolesPermissions['create'];
        $edit = $rolesPermissions['edit'];
        $delete = $rolesPermissions['delete'];
        $print = $rolesPermissions['print'];
        $upload = $rolesPermissions['upload'];
        $datas = PO::where('status', 'OPEN')->paginate(15);

        return view('purchasing.kpi', compact(
            'datas',
            'create',
            'edit',
            'delete',
            'print',
            'upload'
        ));
    }
}
