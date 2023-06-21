<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Role;
use App\Services\RoleRightService;


class RoleController extends Controller 
{

    public function __construct(
        RoleRightService $roleRightService
    ) {
        $this->roleRightService = $roleRightService;
    }
	public function index() 
    {
        
        $rolesPermissions = $this->roleRightService->hasPermissions("Roles Maintenance");

        if (!$rolesPermissions['view']) {
            abort(401);
        }

        $create = $rolesPermissions['create'];
        $edit = $rolesPermissions['edit'];

		$roles = Role::orderBy('name')->get();
		return view('roles.index', compact('roles', 'create', 'edit'));
	}

	public function create() 
    {
		return view('roles.create');
	}

	public function store(Request $request) 
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);
		
        if (Role::where('name', strtoupper($request->name))->exists()) 
		{
			return redirect('/ims/roles')->with('errorMesssage', 'Role Name! already exists.');
        } 
		else 
		{
            $status = $request->has('active');

            Role::create([
                'name' => strtoupper($request->name),
                'description' => $request->description,
                'active' => $status
            ]);

            return redirect('/ims/roles')->with('success', 'Role has been saved!!');
        }	


		// Role::create($request->except('_token'));
		// return redirect('/ims/roles');

	}

	public function edit(Request $request, $id) 
    {

		$role = Role::find($id);

		return view('roles.edit', compact('role'));

	}

	public function update(Request $request, $id) 
    {

        if (Role::where('name', strtoupper($request->name))
            ->where('id', '<>', $id)
            ->exists()
        ) 
		{
            return redirect('/ims/roles')->with('errorMesssage', 'Role Name! already exists.');
        } 
		else 
		{
            $status = $request->has('active');

            Role::find($id)->update([
                'name' => strtoupper($request->name),
                'description' => $request->description,
                'active' => $status
            ]);

            return redirect('/ims/roles')->with('success', 'Role has been updated!!');
        }        

		//$role = Role::find($id);
		//$role->update($request->except('_token'));
		//return redirect('/ims/roles');

	}

}