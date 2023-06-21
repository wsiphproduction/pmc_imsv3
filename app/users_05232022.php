<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable  as AuditableContract;
use OwenIt\Auditing\Contracts\UserResolver;
use OwenIt\Auditing\Auditable;


class users extends Authenticatable implements AuditableContract, UserResolver
{

    use Notifiable;
    use Auditable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = [
        'domainAccount', 'password','name','role','dept','isActive','created_at','updated_at','remember_token','access_rights','role_id','email', 'string_password'
    ];
    protected $auditInclude = [
        'domainAccount', 'password','name','role','dept','isActive','created_at','updated_at','remember_token','access_rights','role_id','email'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public $table='users';

    public function is_an_admin()
    {
        return $this->role == 'admin';
    }

    public static function checkPermission($action){

        $array_permissions = array();

        if($action == 'update_po'){
            $array_permissions = array('purchasing');
        }

        if($action == 'update_payment'){
            $array_permissions = array('accounting');
        }

        if($action == 'create_shipment_schedule'){
            $array_permissions = array('logistics');
        }

        if($action == 'add_drr'){
            $array_permissions = array('receiving');
        }

        
        // Assigned permission to the logged in user
        $array_assigned_permissions = array();

        $rights = Auth::user()->access_rights;
        $modules = explode("|", $rights);

        foreach($modules as $module){
            $array_assigned_permissions[] .= $module;
        }

        // checking permission if exist
        $result = array_intersect($array_permissions, $array_assigned_permissions);

        if (count($result) > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function modules($id)
    {
        $datas = users::where('id',$id)->first();
        $module = "";

        $rights = explode('|',$datas->access_rights);

        foreach($rights as $right){
            $module .= '<span class="label label-default text-uppercase">'.$right.'</span>&nbsp;';
        }

        return $module; 
    }

    public static function employee_lookup()
    {
        // $employees = file_get_contents("https://cms4.webfocusprod.wsiph2.com/pmc_p1/PMCP1-IMSV3/PMC-IMS_V3/api/hris-api.php");
        // $employees = file_get_contents("http://172.16.20.27/ims_v3/api/hris-api.php");
        $employees = file_get_contents(env('API_PATH_LOOKUP')."hris-api.php");


        return $employees;
    }
    public static function resolve()
    {
        return Auth::check() ? Auth::user()->getAuthIdentifier() : null;
    }

}
