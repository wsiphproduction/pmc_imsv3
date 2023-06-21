<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable  as AuditableContract;
use OwenIt\Auditing\Auditable;

class remarks extends Model implements AuditableContract
{

    use Auditable;


	protected $guarded = [];
	
	public $table = 'remarks';

	public $timestamps = true;

    protected $auditInclude = [
        'addedBy',
        'addedDate',
        'remarks',
        'poId',
		'logisticsId',
		'created_at',
		'updated_at'
    ];
}