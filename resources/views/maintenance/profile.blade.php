@extends('layouts.app')

@section('pagecss')
    <link href="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/pages/css/profile.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="page-content">
    <!-- BEGIN BREADCRUMBS -->
    <div class="breadcrumbs">
        <h1>Users</h1>
        <ol class="breadcrumb">
            <li>
                <!-- <a href="/dashboard">Dashboard</a> -->
                <a href="{{ route('ims.dashboard') }}">Dashboard</a>
            </li>
            <li>
                <a href="javascript:;">Settings</a>
            </li>
            <li class="active">Users</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-md-12">
                    
            <!-- BEGIN PROFILE SIDEBAR -->
            <div class="profile-sidebar">
                <!-- PORTLET MAIN -->
                <div class="portlet light profile-sidebar-portlet bordered">
                    <!-- SIDEBAR USERPIC -->
                    <div class="profile-userpic">
                        <img src="{{env('APP_URL')}}/images/user.png') }}" class="img-responsive" alt="">
                    </div>
                    <!-- END SIDEBAR USERPIC -->
                    <!-- SIDEBAR USER TITLE -->
                    <div class="profile-usertitle">
                        <div class="profile-usertitle-name">{{ $user->name }}</div>
                        <div class="profile-usertitle-job">{{ $user->dept }} </div>
                    </div>
                    <!-- END SIDEBAR USER TITLE -->
                </div>
                <!-- END PORTLET MAIN -->
            </div>
            <!-- END BEGIN PROFILE SIDEBAR -->
            <!-- BEGIN PROFILE CONTENT -->
            <div class="profile-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title tabbable-line">
                                <div class="caption caption-md">
                                    <i class="icon-globe theme-font hide"></i>
                                    <span class="caption-subject font-blue-madison bold uppercase">Profile Account</span>
                                </div>
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1_1" data-toggle="tab">Personal Info</a>
                                    </li>
                                    <li>
                                        <a href="#tab_1_3" data-toggle="tab">Change Password</a>
                                    </li>
                                </ul>
                            </div>
                            @foreach ($errors->all() as $error)
                                <p class="text-warning">{{ $error }}</p>
                             @endforeach 
                            @if(Session::has('success'))

                            <script>
                                setTimeout(function() {
                                    $('#success').fadeOut();
                                }, 3000);
                            </script>
                            <div id="success" class="alert alert-success alert-dismissable">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <strong><span class="fa fa-check-square-o"></span> Success!</strong> {{ Session::get('success') }}
                            </div>

                            @endif

                            @if(Session::has('error'))

                            <script>
                                setTimeout(function() {
                                    $('#error').fadeOut();
                                }, 3000);
                            </script>
                            <div id="error" class="alert alert-danger alert-dismissable">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <strong><span class="fa fa-warning"></span> Error!</strong> {{ Session::get('error') }}
                            </div>

                            @endif
                            
                                            
                            <br>                            
                            
                            <div class="portlet-body">
                                <div class="tab-content">
                                    <!-- PERSONAL INFO TAB -->
                                    <div class="tab-pane active" id="tab_1_1">
                                        <form role="form" action="#">
                                            <div class="form-group">
                                                <label class="control-label">Name</label>
                                                <input type="text" value="{{ $user->name }}" class="form-control" /> </div>
                                            <div class="form-group">
                                                <label class="control-label">Domain</label>
                                                <input type="text" value="{{ $user->domainAccount }}" class="form-control" /> </div>
                                            <div class="form-group">
                                                <label class="control-label">Role</label>
                                                <input type="text" value="{{ $user->role }}" class="form-control" /> </div>
                                            <div class="form-group">
                                                <label class="control-label">Department</label>
                                                <input type="text" value="{{ $user->dept }}" class="form-control" /> </div>
                                            <div class="form-group">
                                                <label class="control-label">Access Right</label>
                                                <input class="form-control" type="text" value="{{ $user->access_rights }}">
                                            </div>

                                            <div class="margin-top-10">
                                                <a href="{{ route('ims.dashboard') }}" class="btn default">Cancel</a>
                                            </div>

                                        </form>
                                    </div>
                                    <!-- END PERSONAL INFO TAB -->

                                    <!-- CHANGE PASSWORD TAB -->
                                    <div class="tab-pane" id="tab_1_3">
                                        <form method="post" action="{{ route('user.password-update') }}">
                                            @csrf

                                            <div class="form-group">
                                                <input type="hidden" name="user_id" value="{{$user->id}}">
                                                <label class="control-label">Current Password * </label>
                                                <input type="password" name="current" class="form-control" required/> 
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">New Password * </label>
                                                <input type="password" name="password_new" class="form-control" required /> 
                                                <label class="control-label"></label><i class="font-red" style="font-size: 14px;font-weight:bold;">(Min. 8, alphanumeric, at least 1 upper case, 1 number and 1 special character) </i><br>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">Confirm Password * </label>
                                                <input type="password" name="confirm_password" class="form-control" required />                                                 
                                            </div>                                            
                                            
                                            <div class="margin-top-10">
                                                <button type="submit" class="btn green"> Change Password </button>
                                                <a href="{{ route('ims.dashboard') }}" class="btn default">Cancel</a>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- END CHANGE PASSWORD TAB -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END PROFILE CONTENT -->
        </div>
    </div>
</div>
@endsection

@section('pagejs')
    <script src="{{env('APP_URL')}}/assets/global/scripts/datatable.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
@endsection