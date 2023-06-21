<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Hash;
use DB;
use Storage;
use File;
use Auth;

use App\supplier;
use App\PO;
use App\users;
use App\AuditLogs;
use Faker\Test\Provider\en_ZA\CompanyTest;
use App\Role;
use Illuminate\Support\Facades\Session;
use App\Notifications\EmailNotification;
use App\Services\RoleRightService;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(
        RoleRightService $roleRightService
    ) {
        $this->roleRightService = $roleRightService;
    }
    public function user_index()
    {

        $rolesPermissions = $this->roleRightService->hasPermissions("Users Maintenance");

        if (!$rolesPermissions['view']) {
            abort(401);
        }

        $create = $rolesPermissions['create'];
        $edit = $rolesPermissions['edit'];
        $delete = $rolesPermissions['delete'];
        $print = $rolesPermissions['print'];
        $upload = $rolesPermissions['upload'];

        $users = users::where([['id', '>', 5], ['isActive', '=', 1]])->orderBy('id', 'desc')->paginate(10);

        return view('maintenance.users', compact(
            'users',
            'create',
            'edit',
            'delete',
            'print',
            'upload'
        ));
    }

    public function user_create()
    {
        $rolesPermissions = $this->roleRightService->hasPermissions("Users Maintenance");

        if (!$rolesPermissions['create']) {
            abort(401);
        }
        $roles = Role::where('active', '1')->get();
        return view('maintenance.user_create', compact(
            'roles'
        ));
    }

    public function user_store(Request $req)
    {
        $data = $req->all();
        $req->validate([

            'domainAccount' => 'required|unique:users',
            'email' => 'required|email|unique:users'

        ]);
        $employee = explode(' : ', $req->employee_data);
        $selected_modules = $data['modules'];

        $values = "";

        foreach ($selected_modules as $key => $module) {
            $values .= $module . '|';
            $modules = rtrim($values, '|');
        }
        $roleArr = Role::find($req->input('role_id'));
        $role = $roleArr['name'];
        $user = users::create([
            'domainAccount' => $req->domainAccount,
            'name'          => $employee[0],
            'password'      => Hash::make('password', array('rounds' => 12)),
            'isActive'      => 1,
            'role'          => $role,
            'dept'          => $employee[1],
            'access_rights' => $modules,
            'role_id'        => $req->roleid,
            'email'         => $req->email,
            'remember_token' => str_random(10)
        ]);


        $notification = array(
            'message' => 'User has been added successfully.',
            'alert-type' => 'success'
        );
        Session::flash('success', "A user is added.");
        if ($req->session()->get('success') == "A user is added.") {
            $user->notify(new EmailNotification($user));
        }
        return redirect()->route('users.index')->with('notification', $notification);
    }
    public function user_edit(Request $request, $id)
    {
        $rolesPermissions = $this->roleRightService->hasPermissions("Users Maintenance");

        if (!$rolesPermissions['edit']) {
            abort(401);
        }
        $user = users::find($id);
        $roles = Role::where('active', '1')->get();
        return view('maintenance.user_edit', compact(
            'roles',
            'user'
        ));
    }
    public function user_Update(Request $r)
    {
        $a = '';
        $data = $r->all();
        $employee = explode(' : ', $r->employee_data);
        $selected_modules = $data['modules'];

        $values = "";

        foreach ($selected_modules as $key => $module) {
            $values .= $module . '|';
            $modules = rtrim($values, '|');
        }

        $roleArr = Role::find($r->input('role_id'));
        $role = $roleArr['name'];
        $user = users::find($r->uid)->update([
            'name'          => $employee[0],
            'domainAccount' => $r->domainAccount,
            'role'          => $role,
            'dept'          => $employee[1],
            'access_rights' => $modules,
            'role_id'        => $r->input('role_id'),
            'email'         => $r->input('email'),
        ]);
        if ($user) {
            $notification = array(
                'message' => 'User successfully updated . . .',
                'alert-type' => 'info'
            );
        } else {
            $notification = array(
                'message' => 'User updation failed . . .',
                'alert-type' => 'warning'
            );
        }

        return redirect()->route('users.index')->with('notification', $notification);
    }
    public function destroyUser($id)
    {
        $user = users::find($id);

        $user->update(['isActive' => 0]);

        $notification = array(
            'message' => 'User has been removed successfully.',
            'alert-type' => 'success'
        );

        return redirect()->route('users.index')->with('notification', $notification);

        return redirect()->back();
    }

    public function editUser(Request $r)
    {
        $a = '';
        foreach (($r->access) as $rc) {
            $a    .=  ($rc . "|");
            $ar = rtrim($a, '|');
        }
        $data = users::find($r->uid)->update([
            'name'          => $r->name,
            'domainAccount' => $r->domain,
            'role'          => $r->role,
            'dept'          => $r->dept,
            'access_rights' => $ar
        ]);

        if ($data) {
            $notification = array(
                'message' => 'User successfully updated . . .',
                'alert-type' => 'info'
            );
        } else {
            $notification = array(
                'message' => 'User updation failed . . .',
                'alert-type' => 'warning'
            );
        }

        return back()->with('notification', $notification);
    }

    public function profile($id)
    {
        $user = users::find($id);

        return view('maintenance.profile', compact('user'));
    }

    public function change_password(Request $req)
    {
        $user = users::find($req->user_id);

        $req->validate([

            'password_new' => [
                'required', 'string', 'min:8', 'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&._]/'
            ],
            'confirm_password'  => 'same:password_new'
        ]);

        if ($req->password_new == $req->confirm_password) {
            if (Hash::check($req->current, $user->password)) {
                $user->update(['password'  => Hash::make($req->password_new, array('rounds' => 12))]);

                \Auth::logout();
                return redirect('/ims/login');
            } else {
                return back()->with('error', 'Something is wrong while trying to change the password');
            }
        } else {
            return back()->with('error', 'Something is wrong while trying to change the password');
        }
    }
}
