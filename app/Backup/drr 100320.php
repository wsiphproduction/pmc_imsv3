<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;
use DB;

use App\logistics;
class drr extends Model {

	protected $guarded = [];
	
	public $table = 'drr';

	public $timestamps = false;

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
		$total = logistics::whereNotExists(function($query){
                $query->select(DB::raw(1))->from('drr')->whereRaw('logistics.waybill = drr.waybill');
            })
            ->where('status','Delivered')
            ->count();

        if($total > 0){
            return '<span class="badge badge-danger">'.$total.'</span>';
        }
	}

}