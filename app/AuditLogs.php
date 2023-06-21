<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable  as AuditableContract;
use OwenIt\Auditing\Auditable;

use Carbon\Carbon;

class AuditLogs extends Model implements AuditableContract
{
    
    use Auditable;

	protected $guarded = [];
	
	public $table = 'po_audit_logs';

	public $timestamps = false;

    protected $auditInclude = [
        'id', 
        'poId   ', 
        'action',
        'log_date',
        'affected_field',
        'old_value',
        'new_value',
        'users',
        'field',
        'log_desc',
        'ref_id',

        
    ];

	public function podetails()
    {
        return $this->belongsTo('App\PO', 'poId');
    }

    public function userdetails()
    {
        return $this->belongsTo('App\users', 'users','domainAccount');
    }

	public static function date_for_listing($date) {
        return Carbon::parse($date)->diffForHumans();
    }
}