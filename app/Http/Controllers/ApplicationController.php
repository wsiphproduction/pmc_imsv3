<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ApplicationService;
use App\Role;
use App\Services\RoleRightService;
use Notification;
use App\Application;
use App\Notifications\EmailNofication;
use App\Notifications\ShutdownNotification;
use App\User;
use App\Events\ScheduleShutdown;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;



class ApplicationController extends Controller 
{

    private $applicationService;
    private $roleRightService;

    public function __construct(
        ApplicationService $applicationService,
        RoleRightService $roleRightService
    ) {
        $this->applicationService = $applicationService;
        $this->roleRightService = $roleRightService;
    }

	public function index() 
    {
        
        $rolesPermissions = $this->roleRightService->hasPermissions("Application Maintenance");

        if (!$rolesPermissions['view']) {
            abort(401);
        }

        $create = $rolesPermissions['create'];
        $edit = $rolesPermissions['edit'];
        $delete = $rolesPermissions['delete'];

		$applications = Application::orderBy('scheduled_date','desc')->get();
		return view('applications.index', compact('applications', 'create', 'edit', 'delete'));
	}

	public function create() 
    {
		return view('applications.create');
	}

	public function store(Request $request) 
    {
        $result = $this->applicationService->create($request);
        return $result;
	}

	public function edit(Request $request, $id) 
    {
        $application = Application::find($id);

		return view('applications.edit', compact('application'));
	}

	public function update(Request $request, $id) 
    {

        Application::find($id)->update([
            'scheduled_date' => $request->scheduled_date,
            'scheduled_time' => $request->scheduled_time,
            'reason' => $request->reason
        ]);

        return redirect()->route('maintenance.application.index')->with('success', 'Scheduled Maintenance has been updated successfull!');

	}

    public function destroy($id) 
    {
        return $this->applicationService->destroy($id);
	}

    public function create_indexing()
    {
        $application = DB::update('EXEC runScheduledIndexing');

        if ($application) 
        {
            return redirect()->route('maintenance.application.index')->with('success', 'Reindex Application Database Successful!');            
        } 
        else 
        {
            return redirect()->route('maintenance.application.index')->with('errorMesssage', 'Reindex Application Database Failed.');
        }
    }
    
    public function systemUp()
    {
        Artisan::call('up');
        return redirect()->route('maintenance.application.index')->with('success', 'System back Online!'); 
    }    

    public function systemDown()
    {
        $sessions = glob(storage_path("framework/sessions/*"));
        foreach($sessions as $file){
          if(is_file($file))
            unlink($file);
        }
        Artisan::call('down');
        return redirect('/ims/applications')->with('errorMesssage', 'System is in Maintenance Mode!'); 
    }    

}