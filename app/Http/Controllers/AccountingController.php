<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use \Carbon\Carbon;
use Auth;
use DB;

use App\TempPaymentSchedule;
use App\PaymentSchedule;
use App\PO;
use App\Services\RoleRightService;

class AccountingController extends Controller
{
    public function __construct(
        RoleRightService $roleRightService
    ) {
        $this->roleRightService = $roleRightService;
    }
    public function search()
    {
        $params = Input::all();

        return $this->index($params);
    }

    public function index($param = null)
    {
        $rolesPermissions = $this->roleRightService->hasPermissions("Payments");

        if (!$rolesPermissions['view']) {
            abort(401);
        }

        $create = $rolesPermissions['create'];
        $edit = $rolesPermissions['edit'];
        $delete = $rolesPermissions['delete'];
        $print = $rolesPermissions['print'];
        $upload = $rolesPermissions['upload'];

        $payments = PaymentSchedule::where('isPaid', 0);

        $pageLimit = 10;

        if (isset($param)) {

            if (isset($param['type'])) {
                if ($param['type'] == 1) {
                    $payments->where('isPaid', 1);
                }

                if ($param['type'] == 0) {
                    $payments->where('isPaid', 0);
                }
            }

            if (isset($param['from'])) {
                $from = date('Y-m-d', strtotime($param['from']));
                $to = date('Y-m-d', strtotime($param['to']));

                $payments->whereBetween('paymentDate', [$from, $to])->orderBy('paymentDate', 'asc');
            }

            if (isset($param['poNumber'])) {
                $po = PO::where('poNumber', $param['poNumber'])->first();

                if ($po) {
                    $payments->where('poId', $po->id);
                }
            }
        } else {
            $param = [];
            $payments->orderBy('paymentDate', 'asc');
        }

        $payments = $payments->paginate($pageLimit);

        return view('accounting.index', compact(
            'payments',
            'param',
            'create',
            'edit',
            'delete',
            'print',
            'upload'
        ));
    }

    public function edit($id)
    {
        $po       = PO::where('id', $id)->first();
        $payments = PaymentSchedule::where('poId', $id)->get();
        $estimated_payments = TempPaymentSchedule::where('poId', $id)->get();

        return view('accounting.edit', compact('payments', 'po', 'estimated_payments'));
    }

    public function update(Request $req)
    {
        $data    = $req->all();

        $ids     = $data['payment_id'];
        $amounts = $data['amount'];
        $dates   = $data['date'];
        $ptypes  = $data['payment_type'];

        foreach ($ids as $key => $i) {
            if ($i == 'new' && $amounts[$key] != '') {
                PaymentSchedule::create([
                    'paymentDate' => $dates[$key],
                    'amount' => $amounts[$key],
                    'poId' => $req->poId,
                    'isPaid' => 0,
                    'payment_type' => $ptypes[$key],
                    'addedDate' => Carbon::today(),
                    'addedBy' => auth()->user()->domainAccount
                ]);
            } else {
                PaymentSchedule::where('id', $i)->update([
                    'paymentDate' => $dates[$key],
                    'amount' => $amounts[$key],
                    'payment_type' => $ptypes[$key],
                ]);
            }
        }

        $notification = array(
            'message' => 'Payment schedule has been updated.',
            'alert-type' => 'success'
        );

        return back()->with('notification', $notification);
    }

    public function mark_as_paid(Request $req)
    {
        $data = PaymentSchedule::find($req->ppid);
        $data->isPaid = 1;
        $data->actualPaymentDate = Carbon::today();
        $data->remarks = $req->remarks;
        $data->addedBy = Auth::user()->domainAccount;
        $data->save();

        $this->closePOifPaid($req->poid);

        if ($data) {
            $file_path = '\\\\ftp\FTP\APP_UPLOADED_FILES\ims\\' . $req->pon;

            if (($req->has('uploadFile'))) {
                $files = $req->file('uploadFile');

                if (!file_exists($file_path)) {

                    mkdir($file_path, 0775, true);
                    mkdir($file_path . '\\payment');

                    $destinationPath = $file_path . '\\payment\\' . $req->ppid;
                } else {
                    $destinationPath = $file_path . '\\payment\\' . $req->ppid;
                }

                foreach ($files as $file) {
                    $file->move($destinationPath, $file->getClientOriginalName());
                }
            }
        }

        $notification = array(
            'message' => 'Payment has been paid.',
            'alert-type' => 'success'
        );

        return back()->with('notification', $notification);
    }

    public function overdue()
    {
        $rolesPermissions = $this->roleRightService->hasPermissions("Overdue Payables");

        if (!$rolesPermissions['view']) {
            abort(401);
        }

        $create = $rolesPermissions['create'];
        $edit = $rolesPermissions['edit'];
        $delete = $rolesPermissions['delete'];
        $print = $rolesPermissions['print'];
        $upload = $rolesPermissions['upload'];

        $payments = PaymentSchedule::where('paymentDate', '<', Carbon::today())->where('isPaid', 0)->paginate(10);

        return view('accounting.overdue', compact(
            'payments',
            'create',
            'edit',
            'delete',
            'print',
            'upload'
        ));
    }

    public function destroy(Request $req)
    {
        PaymentSchedule::find($req->pid)->delete();

        $notification = array(
            'message' => 'Payment has been deleted.',
            'alert-type' => 'success'
        );

        return back()->with('notification', $notification);
    }

    public function closePOifPaid($id)
    {
        if (PO::paymentbalance($id) == 0) {
            PO::find($id)->update(['paymentStatus' => 'PAID']);
        }
    }

    public function preview_file(Request $req)
    {

        $today = date('Y-m-d', strtotime(Carbon::today()));

        if (!Storage::exists('/public/' . $today)) {

            Storage::makeDirectory('/public/' . $today, 0775, true);
        }

        $dir = '\\\\ftp\\FTP\\APP_UPLOADED_FILES\\ims\\' . $req->po . '\\payment\\' . $req->pm . '\\' . $req->fileName;
        $dst = storage_path() . '/app/public/' . $today . '/' . $req->fileName;

        copy($dir, $dst);
    }
}
