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

//Models
use App\PO;
use App\supplier;
use App\PaymentSchedule;
use App\drr;
use App\logistics;
use App\remarks;
use App\AuditLogs;

class ProcessesController extends Controller
{

// MAIN PO
    public function dashboard()
    {
        $monthly_completion = PO::whereBetween('expectedCompletionDate',[Carbon::now()->startOfMonth(),Carbon::now()->endOfMonth()])
            ->orderBy('expectedCompletionDate','asc')
            ->get();

        $activities = AuditLogs::orderBy('id','desc')->get();

        return view('po.dashboard',compact('monthly_completion','activities'));
    }

    public function dashboard2()
    {
        $monthly_completion = PO::whereBetween('expectedCompletionDate',[Carbon::now()->startOfMonth(),Carbon::now()->endOfMonth()])
            ->orderBy('expectedCompletionDate','asc')
            ->get();

        return view('po.dashboard2',compact('monthly_completion'));
    }

    public function search()
    {
        $params = Input::all();

        return $this->po_list($params);
    }

    public function po_list($param = null)
    {
        $collection = PO::whereNotNull('id');

        $pageLimit = 10;

        if(isset($param)){

            if(isset($param['poNumber'])){
                $collection->where('poNumber', $param['poNumber']);
            }

            if(isset($param['supplier'])){
                $collection->where('supplier', $param['supplier']);
            }

        } else {

            $param = [];
            $collection->where('status','OPEN')->orderBy('id','desc');

        }

        $collection = $collection->paginate($pageLimit);
        $suppliers = supplier::all();
        
        return view('po.list',compact('collection','param','suppliers'));
    }


    public function details($id)
    {
        $po = PO::where('id',$id)->first();

        $shipments   = logistics::where('poId',$id)->orderBy('expectedDeliveryDate','asc')->get();
        $payments    = PaymentSchedule::where('poId',$id)->orderBy('paymentDate','asc')->get();
        $deliveries  = drr::where('poNumber',$id)->orderBy('id','desc')->get();

        $total_payment    = count($payments);
        $total_shipment   = count($shipments);
        $total_deliveries = count($deliveries); 

        // Files
        //$old_files = file_get_contents("http://172.16.20.27/ims_v3/migration/old_api.php?d=po&id=".$id);
        $old_files = '';


        return view('po.details',compact('po','shipments','payments','deliveries','total_payment','total_shipment','total_deliveries','old_files'));
    }

    public function manual_delete_po(Request $req)
    {   
        $data = PO::find($req->pid)->delete();

        if($data){
            PaymentSchedule::where('poId',$req->pid)->delete();
            logistics::where('poId',$req->pid)->delete();
            drr::where('poNumber',$req->pid)->delete();
        }

        $notification = array(
            'message' => 'PO details, payment schedule, shipment schedule and receiving report hase been deleted.', 
            'alert-type' => 'success'
        );
            
        return back()->with('notification',$notification);
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
            
        return back()->with('notification',$notification);
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

        if(!Storage::exists('/public/'.$today)) {
            
            Storage::makeDirectory('/public/'.$today, 0775, true);
        }

        $dir = '\\\\ftp\\FTP\\APP_UPLOADED_FILES\\ims\\'.$req->po.'\\po\\'.$req->fileName;
        $dst = storage_path().'/app/public/'.$today.'/'.$req->fileName;

        copy($dir, $dst);
    }

    public function preview_drr_file(Request $req)
    {
        $today = date('Y-m-d', strtotime(Carbon::today()));

        if(!Storage::exists('/public/'.$today)) {
            
            Storage::makeDirectory('/public/'.$today, 0775, true);
        }

        $dir = '\\\\ftp\\FTP\\APP_UPLOADED_FILES\\ims\\'.$req->po.'\\mcd\\'.$req->did.'\\'.$req->fileName;
        $dst = storage_path().'/app/public/'.$today.'/'.$req->fileName;

        copy($dir, $dst);
    }


    public function upload(Request $req)
    {
        if(($req->has('uploadFile'))) {
            $files = $req->file('uploadFile');
            $file_path = '\\\\ftp\FTP\APP_UPLOADED_FILES\ims\\'.$req->po;

            if(!file_exists($file_path)) {

                mkdir($file_path, 0775, true);
                mkdir($file_path.'\\po');

                $destinationPath = '\\\\ftp\FTP\APP_UPLOADED_FILES\ims\\'.$req->po.'\\po';
            } else {
                $destinationPath = '\\\\ftp\FTP\APP_UPLOADED_FILES\ims\\'.$req->po.'\\po';
            }

            foreach ($files as $file) {
                $file->move($destinationPath, $file->getClientOriginalName());
            }
        }

        $notification = array(
            'message' => 'Files has been uploaded.', 
            'alert-type' => 'success'
        );
            
        return back()->with('notification',$notification);
    }
############## FILES FUNCTIONS ##############

}
