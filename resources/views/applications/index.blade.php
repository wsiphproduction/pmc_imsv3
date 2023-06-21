@extends('layouts.app')

@section('pagecss')
<link href="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
<link href="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />

<link href="{{env('APP_URL')}}/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{env('APP_URL')}}/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />

@endsection

@section('content')
<div class="page-content">
    <!-- BEGIN BREADCRUMBS -->
    <div class="breadcrumbs">
        <h1>Application Maintenances</h1>
        <ol class="breadcrumb">
            <li>
                <!-- <a href="/dashboard">Dashboard</a> -->
                <a href="{{ route('ims.dashboard') }}">Dashboard</a>
            </li>
            <li>
                <a href="javascript:;">Settings</a>
            </li>
            <li class="active">Application Maintenances</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet light bordered">

                <div class="portlet-title" id="title">
                    <div class="caption">
                        <i class="fa fa-list font-blue-hoki"></i>
                        <span class="caption-subject font-blue-hoki bold uppercase"> Scheduled Shutdown List</span>
                    </div>
                    
                    @if($create)
                        <a href="{{ route('maintenance.application.create') }}" class="btn btn-info pull-right">Create a Scheduled Shutdown</a>
                    @else
                        <button disabled class="btn btn-info pull-right">Create a Scheduled Shutdown</button>
                    @endif 
                    
                </div>


                <div class="table-toolbar">
                    <div class="row">
                        <div class="col-md-12" style="direction:rtl;">
                            <div class="btn-group">
                                <a onclick="return confirm('Are you sure you want to run reindexing?')" href="{{ route('maintenance.application.create_indexing') }}" class="btn sbold green"> Reindex Application Database</a>                                                    
                            </div>
                            <div class="btn-group">
                                <a onclick="return confirm('Are you sure you want to start application?')" href="{{ route('maintenance.application.systemUp') }}" class="btn sbold blue"> Start</a>                                                    
                            </div>
                            <div class="btn-group">
                                <a onclick="return confirm('Are you sure you want to stop application?')" href="{{ route('maintenance.application.systemDown') }}" class="btn sbold red"> Stop</a>                                                    
                            </div>
                        </div>
                    </div>
                </div>                  

                <br>
                <br> 
                <br> 

                @if(session('errorMesssage'))
                    <div id="errdiv" class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                        {!! session('errorMesssage') !!}
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                        <span class="fa fa-check-square-o"></span>
                        {!! session('success') !!}
                    </div>
                @endif
                                
                <br>
                <table class="user_tbl table table-hover" id="sample_101">
                    <thead>
                        <th>Scheduled Date</th>
                        <th>Scheduled Time</th>
                        <th>Reason</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @forelse($applications as $application)
                            <tr>
                                <td> {{$application['scheduled_date']}} </td>
                                <td> {{$application['scheduled_time']}} </td>
                                <td> {{$application['reason']}} </td>                                
                                <td> 
                                    @if($edit)
                                        <a href="{{ route('maintenance.application.edit', $application->id) }}" class="btn btn-primary"> Edit </a>
                                    @else
                                        <button disabled class="btn btn-primary">Edit </button>
                                    @endif            
                                    
                                    @if($delete)
                                        <a data-toggle="modal" href="#remove{{ $application['id' ]}}" class="btn btn-danger"> Delete </a>  
                                    @else
                                        <button disabled class="btn btn-danger">Delete </button>
                                    @endif                                           

                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center"> No Schedule Found </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pull-right"></div>
        </div>
    </div>
</div>


    @foreach($applications as $application)
    
    <div class="modal fade" id="remove{{ $application['id'] }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('maintenance.application.destroy', $application['id']) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title"><b>Confirmation</b></h4>
                    </div>
                    <div class="modal-body"> Are you sure you want to <b>Remove</b> this schedule? </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-circle dark btn-outline" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="remove" class="btn btn-circle red"><span class="fa fa-trash"></span> Remove</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endforeach   

    
@endsection

@section('pagejs')
<script src="{{env('APP_URL')}}/assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>

<script src="{{env('APP_URL')}}/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script type="text/javascript">
    $(document).ready(function(){                
        });
    


          function systemDown(id) {
          $.ajax({
              url: '{!! route('maintenance.application.systemDown') !!}',
              type: 'POST',
              async: false,
              success: function(response) {
                 
              }
          });
      }

</script>

@endsection