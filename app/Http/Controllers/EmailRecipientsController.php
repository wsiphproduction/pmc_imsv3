<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\EmailRecipient;
use App\Services\RoleRightService;


class EmailRecipientsController extends Controller
{

	public function __construct(
        RoleRightService $roleRightService
    ) {
        $this->roleRightService = $roleRightService;
    }

	public function index()
	{
		$rolesPermissions = $this->roleRightService->hasPermissions("Email Recipients Maintenance");

        if (!$rolesPermissions['view']) {
            abort(401);
        }

        $create = $rolesPermissions['create'];
        $edit = $rolesPermissions['edit'];
        $delete = $rolesPermissions['delete'];
        $print = $rolesPermissions['print'];
        $upload = $rolesPermissions['upload'];

		$recipients = EmailRecipient::all();

		return view('email-recipients.index', compact(
			'recipients',
			'create',
			'edit',
			'delete',
			'print',
			'upload'
		));
	}


	public function create()
	{

		return view('email-recipients.create');
	}

	public function store(Request $request)
	{

		EmailRecipient::create($request->except('_token'));

		return redirect('/ims/email-recipients');
	}

	public function edit(Request $request, $id)
	{

		$recipient = EmailRecipient::find($id);

		return view('email-recipients.edit', compact('recipient'));
	}

	public function update(Request $request, $id)
	{

		$recipient = EmailRecipient::find($id);

		$recipient->update($request->except('_token'));

		return redirect('/ims/email-recipients');
	}

	public function destroy($id)
	{

		$recipient = EmailRecipient::find($id);

		$recipient->delete();

		return "deleted";
	}
}
