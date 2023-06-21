@extends('layouts.app')

@section('pagecss')
<link href="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
<link href="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css" />
<link href="{{env('APP_URL')}}/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{env('APP_URL')}}/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />

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
            <!-- BEGIN SAMPLE FORM PORTLET-->
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
            <div class="portlet light bordered">
                <div class="portlet-title" id="title">
                    <div class="caption">
                        <i class="fa fa-list font-blue-hoki"></i>
                        <span class="caption-subject font-blue-hoki bold uppercase"> List Of Users</span>
                    </div>
                    @if($create)
                    <a href="{{ route('user.create') }}" class="btn btn-info pull-right">Create User</a>
                    @else
                    <button disabled href="{{ route('user.create') }}" class="btn btn-info pull-right">Create User</button>
                     @endif
                </div>
                <br>
                <table class="user_tbl table table-hover">
                    <thead>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Assigned Modules</th>
                        <th>Department</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                        <tr>
                            <td>{{ strtoupper($u->name) }}</td>
                            <td>{{ strtoupper($u->domainAccount) }}</td>
                            <td>{{ strtoupper($u->role) }}</td>
                            <td>{!! \App\users::modules($u->id) !!}</td>
                            <td class="text-uppercase">{{ $u->dept }}</td>
                            <td>
                                <div class="btn-toolbar margin-bottom-2">
                                    <div class="btn-group btn-group-sm btn-group-solid">
                                        @if($edit)
                                        <a href="{{ route('user.edit', $u->id) }}" class="btn btn-sm btn-primary button">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        @else
                                        <button disabled href="{{ route('user.edit', $u->id) }}" class="btn btn-sm btn-primary button">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="btn-toolbar margin-bottom-2">
                                    <div class="btn-group btn-group-sm btn-group-solid">
                                        @if($delete)
                                        <a data-toggle="modal" href="#remove{{$u->id}}" data-uid="{{ $u->id }}" class="btn btn-sm btn-danger button">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                        @else
                                        <button disabled data-toggle="modal" href="#remove{{$u->id}}" data-uid="{{ $u->id }}" class="btn btn-sm btn-danger button">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                    <div class="modal fade" id="remove{{$u->id}}" tabindex="-1" role="basic"  aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="{{route('user.destroy',  ['id' =>  $u->id])}}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                        <h4 class="modal-title"><b>Confirmation</b></h4>
                                                    </div>
                                                    <div class="modal-body"> Are you sure you want to <b>Remove</b> this user? </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-circle dark btn-outline" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" name="remove" class="btn btn-circle red"><span class="fa fa-trash"></span> Remove</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pull-right">{{ $users->links() }}&nbsp;</div>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
<script src="{{env('APP_URL')}}/assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>


@endsection