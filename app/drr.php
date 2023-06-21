<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable  as AuditableContract;
use OwenIt\Auditing\Auditable;
use \Carbon\Carbon;
use DB;

use App\logistics;
class drr extends Model implements AuditableContract
{
    
    use Auditable;

	protected $guarded = [];
	
	public $table = 'drr';

	public $timestamps = false;

    protected $auditInclude = [
        'poNumber', 
        'drr', 
        'drrAmount',
        'drrQty',
        'drrDate',
        'invoice',
        '[file]',
        'addedBy',
        'addedDate',
        'waybill'
    ];

	public function ldetails()
    {
        return $this->belongsTo('App\logistics', 'waybill','waybill');
    }

    public function pdetails()
    {
        return $this->belongsTo('App\PO', 'poNumber');
    }

	public static function pendingWaybills()
	{
		// $total = logistics::whereNotExists(function($query){
  //               $query->select(DB::raw(1))->from('drr')->whereRaw('logistics.waybill = drr.waybill')->whereRaw('logistics.poId = drr.poNumber');
  //           })
  //           ->where('status','Delivered')
  //           ->count();
            $total = Logistics::whereExists(function($query){ $query->select(DB::raw(1))->from('po')->whereRaw('po.id = logistics.poId')->where('po.status','OPEN'); })
            ->where('status','Delivered')
            ->count();
        if($total > 0){
            return '<span class="badge badge-danger">'.$total.'</span>';
        }
	}

}