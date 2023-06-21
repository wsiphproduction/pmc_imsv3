<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Permission;
use App\Module;
use App\Services\RoleRightService;

class PermissionController extends Controller 
{

    public function __construct(
        RoleRightService $roleRightService
    ) {
        $this->roleRightService = $roleRightService;
    }
	public function index() 
    {
        
        $rolesPermissions = $this->roleRightService->hasPermissions("Permissions Maintenance");

        if (!$rolesPermissions['view']) {
           abort(401);
        }

        $create = $rolesPermissions['create'];
        $edit = $rolesPermissions['edit'];
  

		$permissions = Permission::orderBy('module_type')
        ->orderBy('description')
        ->get();                        
		return view('permissions.index', compact('permissions', 'create', 'edit'));
	}

	public function create() 
    {
		//return view('permissions.create');

        $modules = Module::orderBy('description','asc')->get();

		return view('permissions.create', compact('modules'));
                
	}

	public function store(Request $request) 
    {
        $request->validate([
            'module_type' => 'required',
            'description' => 'required',
        ]);
		
        if(Permission::where('module_type',$request->module_type)
        ->where('description',$request->description)
        ->exists())
		{
			return redirect('/ims/permissions')->with('errorMesssage', 'Permission Name! already exists.');
        } 
		else 
		{
            $status = $request->has('active');

            Permission::create([
                'module_type' => $request->module_type,
                'description' => $request->description,
                'active' => $status
            ]);

            return redirect('/ims/permissions')->with('success', 'Permission has been saved!!');
        }	
	}

	public function edit(Request $request, $id) 
    {

		$permission = Permission::find($id);
        $modules = Module::orderBy('description','asc')->get();

		return view('permissions.edit', compact('permission','modules'));

	}

	public function update(Request $request, $id) 
    {

        if(Permission::where('module_type',$request->module_type)
        ->where('description',$request->description)
        ->where('id', '<>', $id)
        ->exists()) 
		{
            return redirect('/ims/permissions')->with('errorMesssage', 'Permission Name! already exists.');
        } 
		else 
		{
            $status = $request->has('active');

            Permission::find($id)->update([
                'module_type' => $request->module_type,
                'description' => $request->description,
                'active' => $status
            ]);

            return redirect('/ims/permissions')->with('success', 'Permission has been updated!!');
        }        
	}

}