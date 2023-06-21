<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable  as AuditableContract;
use OwenIt\Auditing\Auditable;

class EmailRecipient extends Model implements AuditableContract
{
    
    use Auditable;
	

	public $table = 'email_recipients';

	public $guarded = [];
	protected $auditInclude = [
        'name', 
        'email', 
        'created_at',
        'updated_at',
    ];

}