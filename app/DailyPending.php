<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable  as AuditableContract;
use OwenIt\Auditing\Auditable; 

use \Carbon\Carbon;

class DailyPending extends Model implements AuditableContract
{
    use Auditable;

	protected $guarded = [];
	
	public $table = 'daily_pending';

	public $timestamps = false;
    protected $fillable = [
        'id',
        'date',
        'total_open_po',
        'overdue_completion',
        'overdue_payable',
        'created_at',
        'updated_at'
    ];
}
