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
        <h1>Permissions</h1>
        <ol class="breadcrumb">
            <li>
                <!-- <a href="/dashboard">Dashboard</a> -->
                <a href="{{ route('ims.dashboard') }}">Dashboard</a>
            </li>
            <li>
                <a href="javascript:;">Settings</a>
            </li>
            <li class="active">Permissions</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet light bordered">

                <div class="portlet-title" id="title">
                    <div class="caption">
                        <i class="fa fa-list font-blue-hoki"></i>
                        <span class="caption-subject font-blue-hoki bold uppercase"> List of Permissions</span>
                    </div>
                    
                    @if($create)
                        <a href="{{ route('permissions.create') }}" class="btn btn-info pull-right">Add Permission</a>
                    @else
                        <button disabled class="btn btn-info pull-right">Add Permission</button>
                    @endif 
                    
                </div>

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
                <table class="user_tbl table table-hover">
                    <thead>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @forelse($permissions as $permission)
                            <tr>
                                <td> {{ strtoupper($permission->module_type) }} </td>
                                <td> {{$permission->description}} </td>
                                <td> 
                                    @if($permission->active)
                                    <i class="font-blue"> Active</i>
                                    @else
                                    <i class="font-red"> Inactive</i>
                                    @endif
                                </td>                                
                                <td> 
                                    @if($edit)
                                        <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-primary"> Edit </a>
                                    @else
                                        <button disabled class="btn btn-primary">Edit </button>
                                    @endif                                        
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center"> No Permission Found </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pull-right"></div>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
<script src="{{env('APP_URL')}}/assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>

<script src="{{env('APP_URL')}}/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script type="text/javascript">


</script>

@endsection