@extends('layouts.app')

@section('pagecss')
<link href="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
<link href="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
<link href="{{env('APP_URL')}}/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endsection

@section('content')
<div class="page-content">
    <!-- BEGIN BREADCRUMBS -->
    <div class="breadcrumbs">
        <h1>Create Permission</h1>
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
        
            <form class="col-md-4 col-md-offset-4" style="margin-top: 20px;" method="POST" action="{{ route('permissions.store') }}">
                @csrf 

                <div class="form-group">
                    <label>Status </label>
                    <input type="checkbox" name="active" id="active">
                </div>                

                <div class="form-group">
                    <label>Module <span class="required" aria-required="true"> * </span></label>

                    <select required name="module_type" id="module_type" class="form-control"> 
                        @foreach($modules as $module)
                            <option value="{{ $module['description'] }}">{{ $module['description'] }}</option>
                        @endforeach
                    </select>                      
                    
                </div>

                <div class="form-group">
                    <label>Description <span class="required" aria-required="true"> * </span></label>
                    <input type="description" name="description" class="form-control" required maxlength="50" placeholder="Description">
                </div>

                <a href="{{ route('permissions.index') }}" class="btn btn-primary pull-left">Cancel </a>
                <button class="btn btn-primary pull-right"> Create </button>

            </form>

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
@endsection