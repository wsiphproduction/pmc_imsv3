<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable  as AuditableContract;
use OwenIt\Auditing\Auditable;
use \Carbon\Carbon;
use DB;

use App\PO;
use App\remarks;
class logistics extends Model implements AuditableContract
{

    use Auditable;

	protected $guarded = [];
	
	public $table = 'logistics';

	public $timestamps = false;
    protected $auditInclude = [
        'expectedDeliveryDate', 
        'portArrivalDate', 
        'customClearedDate',
        'poId',
        'waybill',
        'status',
        'customStartDate',
        'actualDeliveryDate',
        'addedBy',
        'created_at',
        'log_type',
        'departure_dt',
        'actualManufacturingDate',
        'eta',
        'port',
        'ssdp',
        'ron',
        'calendar_date',
        'tracker',
        'site',
        'pickup_date',
        'date_actual_pickup',
        'delivered_dt',
		'arrival_date',
		'destination_site',
		'designated_tracker',
		'delivery_date',
		'received_date'

    ];
    public function po_details()
    {
        return $this->belongsTo('App\PO', 'poId');
    }

    public static function totalOverdueDeliveries()
    {
        $total = logistics::where('expectedDeliveryDate','<',Carbon::today())->where('status','<>','Delivered')->count();

        return $total;
    }

    public static function count_shipments($id)
    {
        $total = logistics::where('poId',$id)->count();

        return $total;
    }

    public static function totalLateDeliveries($from,$to)
    {
        $total = logistics::where('status','Delivered')->whereRaw('actualDeliveryDate > expectedDeliveryDate')->whereBetween('actualDeliveryDate',[$from,$to])->count();

        return $total;
    }

    public static function totalOnTimeDeliveries($from,$to)
    {
        $total = logistics::where('status','Delivered')->whereRaw('actualDeliveryDate <= expectedDeliveryDate')->whereBetween('actualDeliveryDate',[$from,$to])->count();

        return $total;
    }

    public static function totalOnTimeClearing($from,$to)
    {
        $row = 0;

        $datas = logistics::where('status','Delivered')->whereBetween('actualDeliveryDate',[$from,$to])->get();

        foreach($datas as $d){
            $days = Carbon::parse($d->customStartDate)->diffInDays($d->customClearedDate, false);
            
            if($days <= 10){
                $row++;
            }
        }

        return $row;
    }

    public static function totalDelayedClearing($from,$to)
    {

        $row = 0;
        $datas = logistics::where('status','Delivered')->whereBetween('actualDeliveryDate',[$from,$to])->get();

        foreach($datas as $d){
            $days = Carbon::parse($d->customStartDate)->diffInDays($d->customClearedDate, false);
            
            if($days > 10){
                $row++;
            }
        }

        return $row;
    }

    

    public static function delivery_type($type)
    {
        if($type == 'partial'){
            return '<span class="label label-warning">PARTIAL</span>';
        } else {
            return '<span class="label label-success">FULL</span>';
        }
    }

    public static function remarks($id)
    {
        $data = remarks::where('logisticsId',$id)->latest('id')->get();
        $result = count($data);

        if($result > 0){
            return $data[0]['remarks'];
        } else {
            return "N/A";
        }
        
    }

    public static function shipment_status($id)
    {
        $total = logistics::where('poId',$id)->count();
        $total_delivered = logistics::where('poId',$id)->where('status','Delivered')->count();

        return $total_delivered.'/'.$total;
    }




    public static function drr_remarks($waybill){

        $log = logistics::where('waybill',$waybill)->first();
        $drr = drr::where('waybill',$waybill)->first();

        $datediff = Carbon::parse($log->actualDeliveryDate)->diffInDays($drr->drrDate);

        if($datediff > 2){
            return '<span style="font-size:11px;" class="label label-danger">'.$datediff.' days late</span>';
        } else {
            return '<span style="font-size:11px;" class="label label-success">ON TIME</span>';
        }

    }

	public static function rr_date_diff($waybill,$rr_dt){
		
		if(logistics::where('waybill',$waybill)->exists()){

			$log = logistics::where('waybill',$waybill)->first();

			$diff = Carbon::parse($log->actualDeliveryDate)->diffInDays($rr_dt).' day(s)';
			return $diff;
		}
	}

    public static function inTransit($id){
        
        $data = logistics::where('id',$id)->first();

        if($data->portArrivalDate <> NULL){
            $diffArrivalDate = Carbon::parse($data->departure_dt)->diffInDays($data->portArrivalDate);

            return date('M d, Y', strtotime($data->departure_dt)).' - '.date('M d, Y', strtotime($data->portArrivalDate)).'<br><span style="color:red;">'.$diffArrivalDate.' day(s)</span>';
        }
    
    }

	public static function customClearing($id){
        
        $data = logistics::where('id',$id)->first();

        if($data->customStartDate == NULL && $data->customClearedDate == NULL){

        } else {
            if($data->customStartDate <> NULL && $data->customClearedDate == NULL){
                return date('M d, Y', strtotime($data->customStartDate));
            } else {
                return date('M d, Y', strtotime($data->customStartDate)).' - '.date('M d, Y', strtotime($data->customClearedDate));
            }
        }   
    
	}

	public static function customDateDiff($id){
        
        $data = logistics::where('id',$id)->first();

        if($data->customStartDate <> NULL && $data->customClearedDate == NULL){
            $diffStartDate = Carbon::parse($data->portArrivalDate)->diffInDays($data->customStartDate);

            return '<span style="color:red;">'.$diffStartDate.' day(s)</span>';
        }

        if($data->customStartDate <> NULL && $data->customClearedDate <> NULL){
            $diffStartDate = Carbon::parse($data->portArrivalDate)->diffInDays($data->customStartDate);
            $diffClearDate = Carbon::parse($data->customStartDate)->diffInDays($data->customClearedDate);

            return '<span style="color:red;">'.$diffStartDate.' day(s)</span><span style="color:red;"> - '.$diffClearDate.' day(s)</span>';
        }
    
	}

	public static function actualDeliveryDate($id){
        
        $data = logistics::where('id',$id)->first();

        if($data->status == 'Delivered'){
        	$diff = Carbon::parse($data->customClearedDate)->diffInDays($data->actualDeliveryDate);
        	return date('M d, Y', strtotime($data->actualDeliveryDate)).' - '.date('M d, Y', strtotime($data->expectedDeliveryDate)).'<br><span style="color:red;">'.$diff.' day(s)</span>';
        } else {
            return date('M d, Y', strtotime($data->expectedDeliveryDate));
        }
    
	}

    public static function shipmentRemarks($id)
    {
        $data = logistics::where('id',$id)->first();
        $po   = PO::where('id',$data->poId)->first();

        if($data->status == 'Delivered' && $data->log_type == 'partial'){
            $diff = Carbon::parse($data->actualDeliveryDate)->diffInDays($data->expectedDeliveryDate);

            if($data->actualDeliveryDate > $data->expectedDeliveryDate){
                return 'Shipment was delayed '.$diff.' day(s)';
            } else {
                return 'Shipment was delivered on/before the supplier committed date';
            }
        }

        if($data->status == 'Delivered' && $data->log_type == 'full'){
            if($data->actualDeliveryDate > $po->expectedCompletionDate){
                return "Shipment wasn't delivered on the scheduled due date";
            } else {
                return "Shipment was delivered prior to the expected due date based on PO";
            }
        }   
    }


    public static function delivery_status($poid)
    {
        $qry = logistics::where('poId',$poid);
        $total = $qry->count();
        $delivered = $qry->where('status','Delivered')->count();

        if($total > 0){

            $output = $delivered.'/'.$total.' DELIVERED';

        } else {
            $output = 'NO DELIVERIES CREATED';
        }

        return $output;

    }

    public static function delivery_balance($poid)
    {
        $qry = logistics::where('poId',$poid);
        $total = $qry->count();
        $delivered = $qry->where('status','Delivered')->count();
        $output = 1;

        if($total > 0){
            $output = $total - $delivered; 
        } 
        
        return $output;

    }

    public static function latest_delivery_status($poid)
    {

        $qry = logistics::where('poId',$poid)->where('status','<>','Pending');
        $count = $qry->count();
        $rs = $qry->first();

        if($count > 0){

            $res = $rs->status;

        } else {

            $res = 'Pending';
        }

        return $res;

    }

}