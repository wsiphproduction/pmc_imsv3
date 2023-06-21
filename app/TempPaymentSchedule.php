<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable  as AuditableContract;
use OwenIt\Auditing\Auditable;

use \Carbon\Carbon;

class TempPaymentSchedule extends Model implements AuditableContract
{

    use Auditable;

	protected $guarded = [];
	
	public $table = 'temp_payment_schedule';

	public $timestamps = false;

    protected $auditInclude = [
        'poId',
        'paymentDate',
        'amount',
        'payment_type',
		'addedBy',
		'addedAt'
    ];
}
