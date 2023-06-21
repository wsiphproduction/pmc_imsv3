<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable  as AuditableContract;
use OwenIt\Auditing\Auditable;

class supplier extends Model implements AuditableContract
{

    use Auditable;


	protected $guarded = [];
	
	public $table = 'supplier';

	public $timestamps = false;

    protected $auditInclude = [
        'addedDate',
        'addedBy',
        'name',
        'contact',
		'address',
		'LTO_validity',
		'Contact_Person',
		'Supplier_Code'
    ];
}