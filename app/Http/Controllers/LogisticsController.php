<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Auth;
use DB;

use App\post;
use App\logistics;
use App\PO;
use App\drr;
use App\Company;
// use App\Mail\Customcleared;
// use Illuminate\Support\Facades\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use App\Services\RoleRightService;


class LogisticsController extends Controller
{
    public function __construct(
        RoleRightService $roleRightService
    ) {
        $this->roleRightService = $roleRightService;
    }
    public function dashboard()
    {
        $rolesPermissions = $this->roleRightService->hasPermissions("Logistic Dashboard");

        if (!$rolesPermissions['view']) {
            abort(401);
        }
        return view('logistics.dashboard');
    }

    public function shipment_summary_filter()
    {
        $params = Input::all();

        return $this->shipment_summary($params);
    }

    public function shipment_summary($param = null)
    {
        $rolesPermissions = $this->roleRightService->hasPermissions("Shipments Summary");

        if (!$rolesPermissions['view']) {
            abort(401);
        }
        $datas = logistics::where('status', 'Delivered');

        $pageLimit = 10;

        if (isset($param)) {

            $datas->whereBetween('actualDeliveryDate', ["" . date('Y-m-d', strtotime($param['from'])) . "", "" . date('Y-m-d', strtotime($param['to'])) . ""])
                ->orderBy('actualDeliveryDate', 'desc');
        } else {

            $param = [];
            $datas->whereBetween('actualDeliveryDate', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->orderBy('actualDeliveryDate', 'desc');
        }

        $datas = $datas->paginate($pageLimit);

        return view('logistics.shipment_summary', compact(
            'datas',
            'param'
        ));
    }

    public function filter()
    {
        $params = Input::all();

        return $this->shipment_kpi($params);
    }

    public function shipment_kpi($param = null)
    {
        $rolesPermissions = $this->roleRightService->hasPermissions("Shipments KPI");

        if (!$rolesPermissions['view']) {
            abort(401);
        }

        $datas = logistics::where('status', 'Delivered');

        $pageLimit = 10;

        if (isset($param)) {

            $datas->whereBetween('actualDeliveryDate', ["" . date('Y-m-d', strtotime($param['from'])) . "", "" . date('Y-m-d', strtotime($param['to'])) . ""])
                ->orderBy('actualDeliveryDate', 'desc');
        } else {

            $param = [];
            $datas->whereBetween('actualDeliveryDate', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->orderBy('actualDeliveryDate', 'desc');
        }

        $datas = $datas->paginate($pageLimit);

        return view('logistics.shipment_kpi', compact('datas', 'param'));
    }

    public function rr_summary()
    {
        $rolesPermissions = $this->roleRightService->hasPermissions("DRR Summary");

        if (!$rolesPermissions['view']) {
            abort(401);
        }
        $pendings = logistics::whereNotExists(function ($query) {
            $query->select(DB::raw(1))->from('drr')->whereRaw('logistics.waybill = drr.waybill')->whereRaw('logistics.poId = drr.poNumber');
        })
            ->where('status', 'Delivered')
            ->orderBy('actualDeliveryDate', 'asc')
            ->get();


        $datas = drr::where('waybill', '<>', 'services')->whereBetween('drrDate', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->get();

        return view('logistics.rr_summary', compact('datas', 'pendings'));
    }

    public function filter_completion()
    {
        $params = Input::all();

        return $this->completion($params);
    }
    public function completion($param = NULL)
    {
        $rolesPermissions = $this->roleRightService->hasPermissions("Estimated PO Completion");

        if (!$rolesPermissions['view']) {
            abort(401);
        }

        $create = $rolesPermissions['create'];
        $edit = $rolesPermissions['edit'];
        $delete = $rolesPermissions['delete'];
        $print = $rolesPermissions['print'];
        $upload = $rolesPermissions['upload'];

        $collection = PO::where('status', 'OPEN');


        if (isset($param)) {

            if (isset($param['from'])) {
                $collection->whereBetween('expectedCompletionDate', ["" . date('Y-m-d', strtotime($param['from'])) . "", "" . date('Y-m-d', strtotime($param['to'])) . ""])
                    ->orderBy('expectedCompletionDate', 'asc');
            }
        } else {

            $param = [];
            $collection->whereBetween('expectedCompletionDate', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->orderBy('expectedCompletionDate', 'asc');
        }

        $collection = $collection->get();

        return view('logistics.completion_dt', compact(
            'collection',
            'param',
            'create',
            'edit',
            'delete',
            'print',
            'upload'
        ));
    }


    public function create($id)
    { 
        
        $poId = $id;
        
        return view('logistics.create', compact('poId'));
    }

   

    public function waybills($id)
    {
        
        $poId = $id;
        $waybills = logistics::where('poId', $id)->get();
        // dd($waybills);

        $choose = post::get();
        $pick = company::get();
        

        return view('logistics.waybills', compact('waybills', 'poId'))->with('choose', $choose)->with('pick', $pick);
    }
    
    public function store(Request $req)
    {
    
        $data  = $req->all();

        // dd($data);
        $dates = $data['dates'];

        foreach ($dates as $key => $i) {
            $delivery = new logistics();
            $delivery->waybill = 'shipment';
            $delivery->poId = $req->poid;
            $delivery->expectedDeliveryDate = $i;
            $delivery->log_type = $req->rcounter == $key ? 'full' : 'partial';
            $delivery->status = 'Pending';
            $delivery->addedBy = Auth::user()->domainAccount;
            $delivery->save();
        }

        $notification = array(
            'message' => 'Shipment schedule successfully saved.',
            'alert-type' => 'success'
        );
        //return 'success';
        return redirect()->route('view.shipment.schedule', $req->poid)->with('notification', $notification);
    }

    public function edit($id)
    {
        $shipments = logistics::where('poId', $id)->get();
        $counter = count($shipments);
        $poId = $id;

        return view('logistics.edit', compact('shipments', 'counter', 'poId'));
    }

    public function update(Request $req)
    {
        $data = $req->all();
        $ids  = $data['ship_id'];
        $type = $data['log_type'];
        $waybill = $data['waybill'];
        $dates = $data['dates'];

        foreach ($ids as $key => $i) {
            if ($i == 'new') {
                logistics::create([
                    'log_type' => $type[$key],
                    'waybill' => $waybill[$key],
                    'expectedDeliveryDate' => $dates[$key],
                    'poId' => $req->poid,
                    'status' => 'Pending'
                ]);
            } else {
                logistics::where('id', $i)->update([
                    'log_type' => $type[$key],
                    'waybill' => $waybill[$key],
                    'expectedDeliveryDate' => $dates[$key],
                ]);
            }
        }

        $notification = array(
            'message' => 'Shipment schedule successfully updated.',
            'alert-type' => 'info'
        );

        return back()->with('notification', $notification);
    }

    public function delete($id)
    {

        $shipment = logistics::find($id);
        $shipment->delete();
        return "true";
    }

    public function addWaybill(Request $req)
    {
        $logistics = logistics::find($req->shipment_id);


        $waybill = logistics::where('id', $req->shipment_id)->update([
            'actualManufacturingDate' => $req->manufacture_dt,
            'waybill' => $req->waybill,
            'departure_dt' => $req->departure_dt,
            'status' => 'In-Transit',
            'addedBy' => Auth::user()->domainAccount
        ]);

        $notification = array(
            'message' => 'Waybill successfully updated',
            'alert-type' => 'success'
        );
        $this->send_email($req->shipment_id, 'In-transit');
        return back()->with('notification', $notification);
    }

    public function inTransit(Request $req)
    {
        // dd($req->arrival_dt);
        $waybill = logistics::where('id', $req->shipment_id)->update([
            
            'portArrivalDate' => $req->arrival_dt,
            'port' => $req->port,
            'eta' => $req->eta,
            'addedBy' => Auth::user()->domainAccount
            
        ]);

        $notification = array(
            'message' => 'Shipment status successfully updated',
            'alert-type' => 'success'
        );

        // dd($req->shipment_id);
        $this->send_email($req->shipment_id, 'In-transit');
        return back()->with('notification', $notification);
        
    }

    public function customClearing(Request $req)
    {

        $logistic_record = Logistics::whereId($req->shipment_id)->first();
        $send_email_remark = 0;
        if (is_null($logistic_record->customStartDate)) {
            $send_email_remark = 1;
        }
        $upd = Logistics::whereId($req->shipment_id)->update([
            'customStartDate' => $req->start,
            'customClearedDate' => $req->cleared,
            'ssdp' => $req->ssdp,
            'broker_agent' => $req->broker_agent,
            'status' => 'Custom Clearing'
        ]);

        // $data = logistics::find($req->shipment_id);
        // $data->customStartDate = $req->start;
        // $data->customClearedDate = $req->cleared;
        // $data->status = 'Custom Clearing';
        // $data->save();


        $notification = array(
            'message' => 'Shipment status successfully updated.',
            'alert-type' => 'success'
        );

        if ($send_email_remark == 1) {
            $this->send_email($req->shipment_id, 'Customs Clearing');
        }

        return back()->with('notification', $notification);
    }

    public function pickUp(Request $req)
    {

        // dd('hello');
        $data = logistics::find($req->shipment_id);
        $data->status = 'For Pick-Up';
        $data->save();


        $upd = Logistics::whereId($req->shipment_id)->update([
            'tracker' => $req->tracker,
            'site' => $req->site,
            'ron' => $req->ron,
            'pickup_date' => $req->pickup_date,
            'date_actual_pickup' => $req->date_actual_pickup
            // 'status' => 'Custom Clearing'
        ]);


        $notification = array(
            'message' => 'Shipment status successfully updated.',
            'alert-type' => 'success'
        );
        $this->send_email($req->shipment_id, 'Pickup');
        return back()->with('notification', $notification);
    }

    public function delivered(Request $req)
    {
        $data = logistics::find($req->shipment_id);
        $data->actualDeliveryDate = $req->delivered_dt;
        $data->status = 'Delivered';
        $data->addedBy = Auth::user()->domainAccount;
        $data->save();

        
        $upd = logistics::whereId($req->shipment_id)->update([
        'actualDeliveryDate' => $req->delivered_dt,
         'delivered_dt' => $req->delivered_dt,
         'arrival_date' => $req->arrival_date,
         'destination_site' => $req->destination_site,
         'designated_tracker' => $req->designated_tracker,
         'delivery_date' => $req->delivery_date,
         'received_date' => $req->received_date
    ]);




        $notification = array(
            'message' => 'Shipment status successfully updated.',
            'alert-type' => 'success'
        );
        $this->send_email($req->shipment_id, 'Delivered');
        return back()->with('notification', $notification);
    }

    public function send_email($logistic_id, $status)
    {
        return true;
        $l = logistics::where('id', $logistic_id)->first();
        $p = PO::where('id', $l->poId)->first();


        require '../vendor/autoload.php';


        $mail = new PHPMailer(true);

        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;    
        $mail->isSMTP();
        $mail->Host       = 'smtp.philsaga.com';
        $mail->Port       = 25;

        //Recipients
        // loop through email default table 
        $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        // $mail->addBCC('dutablante@philsaga.com', 'Dann Tablante');     // Add a recipient
        // $mail->addBCC('mraquino@philsaga.com', 'Michael Aquino'); 
        // $mail->addBCC('purchasing@philsaga.com', 'Purchasing');
        // $mail->addBCC('raseroy@philsaga.com', 'Ruby Jane A. Seroy');
        // $mail->addBCC('ebcanton@philsaga.com', 'Elsie B. Canton');

        $cc = \App\EmailRecipient::all();
        if (count($cc)) {
            foreach ($cc as $rcpt) {
                $mail->addBCC($rcpt->email, $rcpt->name);
            }
        }

        //$mail->addReplyTo('info@example.com', 'Information');             
        $emails = explode(",", $p->email_receivers);
        if (count($emails) > 0) {
            foreach ($emails as $email) {
                if (strlen($email) > 2) {
                    $mail->addAddress($email);
                }
            }
        }

        $mail->msgHTML(file_get_contents(env('NATIVE_PHP_PATH').'email_sender.php?status=' . urlencode($status) . '&ponumber=' . urlencode($p->poNumber) . '&supplier=' . urlencode($p->supplier_name->name) . '&order_date=' . urlencode($p->orderDate) . '&rq_number=' . urlencode($p->rq) . '&rq_date=' . urlencode($p->rq_date) . '&expected_delivery=' . urlencode($l->expectedDeliveryDate) . '&po_id=' . urlencode($l->poId)), __DIR__);
        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'PO# ' . $p->poNumber . ' (' . strtoupper($p->supplier_name->name) . ') Logistics Update';
        // $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
    }
}