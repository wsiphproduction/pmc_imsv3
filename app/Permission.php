<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable  as AuditableContract;
use OwenIt\Auditing\Auditable;


class Permission extends Model  implements AuditableContract
{

    use Auditable;
	

	public $table = 'permissions';

	public $guarded = [];


    protected $auditInclude = [
        'description',
        'module_type',
        'created_at',
        'updated_at',
		'active'
    ];
}