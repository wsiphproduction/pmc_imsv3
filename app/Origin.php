<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable  as AuditableContract;
use OwenIt\Auditing\Auditable;


class Origin extends Model  implements AuditableContract
{
	
    use Auditable;
	public $table = 'country_origin';

    protected $auditInclude = [
        'country',
        'country_code'
    ];
}