<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use \Carbon\Carbon;
use Auth;
use DB;

use App\drr;
use App\PO;
use App\logistics;
use App\PaymentSchedule;
use App\TempPaymentSchedule;
use App\AuditLogs;


class McdController extends Controller
{
    public function pendings()
    {
        // $datas = logistics::whereNotExists(function($query){ $query->select(DB::raw(1))->from('drr')->whereRaw('logistics.waybill = drr.waybill'); })
        //     ->where('status','Delivered')
        //     ->orderBy('actualDeliveryDate','asc')
        //     ->get();
         $datas = Logistics::whereExists(function($query){ $query->select(DB::raw(1))->from('po')->whereRaw('po.id = logistics.poId')->where('po.status','OPEN'); })
            ->where('status','Delivered')
            ->orderBy('actualDeliveryDate','asc')
            ->get();
        return view('receiving.index',compact('datas'));
    }

    public function create($waybill,$po)
    {
        $shipment = logistics::where('waybill',str_replace("qwjz", "/",$waybill))->where('poId',$po)->first();
        $po       = PO::where('id',$po)->first();
        //dd($shipment);
        return view('receiving.create',compact('shipment','po'));
    }

    public function drr_store(Request $req)
    {   
        $data = drr::create([
            'poNumber' => $req->poId,
            'drr'      => $req->drn,
            'drrAmount'=> $req->drrAmount,
            'drrQty'   => $req->drrQty,
            'drrDate'  => Carbon::today(),
            'invoice'  => $req->inv,
            'addedBy'  => Auth::user()->domainAccount,
            'waybill'  => $req->waybill,
            'addedDate'=> Carbon::now()
        ]);

        $this->audit($data->id,$req);
        $this->closePOifDelivered($req->poId);


        if($data){

            TempPaymentSchedule::create([
                'poId' => $req->poId,
                'paymentDate' => $req->payment_date,
                'amount' => $req->payment_amount,
                'payment_type' => $req->payment_type,
                'addedBy' => Auth::user()->domainAccount,
                'addedAt' => Carbon::now()
            ]);

            PaymentSchedule::create([
                'paymentDate' => $req->payment_date,
                'amount' => $req->payment_amount,
                'poId' => $req->poId,
                'isPaid' => 0,
                'addedBy' => Auth::user()->domainAccount,
                'payment_type' => $req->payment_type
            ]);


            $file_path = '\\\\ftp\FTP\APP_UPLOADED_FILES\ims\\'.$req->poNumber;

            if(($req->has('uploadFile'))) {
                $files = $req->file('uploadFile');

                if(!file_exists($file_path)) {

                    mkdir($file_path, 0775, true);
                    mkdir($file_path.'\\mcd');

                    $destinationPath = $file_path.'\\mcd\\'.$data->id;
                } else {
                    $destinationPath = $file_path.'\\mcd\\'.$data->id;
                }

                foreach ($files as $file) {
                    $file->move($destinationPath, $file->getClientOriginalName());
                }
            }            
        }

        $notification = array(
            'message' => 'Delvery Receiving Report has been added.', 
            'alert-type' => 'info'
        );
        return redirect()->route('receiving.index')->with('notification',$notification);
    }

    public function audit($id,$req){
        AuditLogs::create([
            'ref_id' => $id,
            'poId' => $req->poId,
            'action' => 'insert',
            'log_date' => Carbon::now(),
            'users' => Auth::user()->domainAccount,
            'log_desc' => 'created the delivery receiving report '.$req->drn.' of waybill '.$req->waybill
        ]);
    }

    public function closePOifDelivered($id){
        
        $balance = PO::DeliveryBalanceStatic($id);

        if($balance == 0){
            PO::find($id)->update([ 'deliveryStatus' => 'DELIVERED' ]);
        }
    }

    public function filter_drr_summary()
    {
        $params = Input::all();

        return $this->summary($params);
    }

    public function summary($param = null)
    {
        $datas = drr::whereNotNull('id');

        $pageLimit = 10;

        if(isset($param)){

            $datas->whereBetween('drrDate',["".date('Y-m-d',strtotime($param['from']))."","".date('Y-m-d',strtotime($param['to'])).""])
                ->orderBy('drrDate','desc');

        } else {

            $param = [];
            $datas->whereBetween('drrDate',[Carbon::now()->startOfMonth(),Carbon::now()->endOfMonth()])->orderBy('drrDate','desc');

        }

        $datas = $datas->paginate($pageLimit);

        return view('receiving.summary',compact('datas','param'));
    }

    public function create_services($id)
    {
        $po = PO::where('id',$id)->first();

        return view('receiving.services',compact('po'));
    }


}
