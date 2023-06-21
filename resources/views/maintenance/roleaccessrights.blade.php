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
        <h1>Role Access Rights</h1>
        <ol class="breadcrumb">
            <li>
                <!-- <a href="/dashboard">Dashboard</a> -->
                <a href="{{ route('ims.dashboard') }}">Dashboard</a>
            </li>
            <li>
                <a href="javascript:;">Settings</a>
            </li>
            <li class="active">Role Access Rights</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet light bordered">

                <div class="portlet-title" id="title">
                    <div class="caption">
                        <i class="fa fa-list font-blue-hoki"></i>
                        <span class="caption-subject font-blue-hoki bold uppercase"> List of Role Access Rights</span>
                    </div>
                                        
                </div>

                @if (count($errors) > 0)
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                    <ul class="nav">
                        @foreach ($errors->all() as $error)
                        <li><span class="fa fa-exclamation"></span>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(session('success'))
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                    <span class="fa fa-check-square-o"></span>
                    {!! session('success') !!}
                </div>
                @endif
                @if(session('failed'))
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                    <span class="a fa-exclamation"></span>
                    {!! session('failed') !!}
                </div>
                @endif
                                
                <br>
            <div class="portlet light bordered">

                <form id="form" action="{{ route('maintenance.roleaccessrights.store') }}" method="POST">

                    <input type="hidden" id="create" name="create" value="{{ $create }}">
                    <input type="hidden" name="roles_permissions" id="roles_permissions" value="">
                    @csrf

                    <div class="portlet-title">
                        
                        <div class="actions">
                            <div class="form-group form-inline" style="display:inline;margin-right:10px">

                                <label class="control-label" style="margin-right:20px; margin-left:10px; margin-top:10px">Role </label>
                                
                                <select required name="roleid" id="roleid" class="form-control">
                                    @foreach($roles as $role)
                                    <option value="{{ $role['id'] }}">{{ $role['name'] }}</option>
                                    @endforeach
                                </select>
                                
                            </div>
                            @if($create)
                                <button type="submit" class="btn blue" id="saveRolePermission">
                                    <i class="fa fa-save"></i>&nbsp; Save Changes
                                </button>
                            @else
                                <button disabled type="submit" class="btn blue" id="saveRolePermission">
                                    <i class="fa fa-save"></i>&nbsp; Save Changes
                                </button>
                            @endif
                        </div>
                    </div>                     
                    
                    <div class="portlet-body">
                        <div class="table-scrollable">
                            <table class="table table-hover" style="width:100%;">
                                <thead class="thead-light">
                                    <tr class="green">
                                        <th>Permission List</th>
                                        <th>View</th>
                                        <th>Create</th>
                                        <th>Update</th>
                                        <th>Delete</th>
                                        <th>Print</th>
                                        <th>Upload</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($modules as $module)
                                    <tr>
                                        <td width="50%">
                                            <div class="caption custom-padding">
                                                <span class="caption-subject font-green bold uppercase">{{ strtoupper($module['description'])}}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="md-checkbox custom-padding">
                                                <input type="checkbox" class="md-check" data-role="{{$module['id']}}_view" data-module="{{$module['id']}}_view" onclick="checkPermission(this.id)" id="{{$module['id']}}_view">
                                                <label for="{{$module['id']}}_view">
                                                    <span></span>
                                                    <span span class="check"></span>
                                                    <span class="box"></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="md-checkbox custom-padding">
                                                <input type="checkbox" class="md-check" data-role="{{$module['id']}}_create" data-module="{{$module['id']}}_create" onclick="checkPermission(this.id)" id="{{$module['id']}}_create">
                                                <label for="{{$module['id']}}_create">
                                                    <span></span>
                                                    <span span class="check"></span>
                                                    <span class="box"></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="md-checkbox custom-padding">
                                                <input type="checkbox" class="md-check" data-role="{{$module['id']}}_edit" data-module="{{$module['id']}}_edit" id="{{$module['id']}}_edit" onclick="checkPermission(this.id)">
                                                <label for="{{$module['id']}}_edit">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="md-checkbox custom-padding">
                                                <input type="checkbox" class="md-check" data-role="{{$module['id']}}_delete" data-module="{{$module['id']}}_delete" id="{{$module['id']}}_delete" onclick="checkPermission(this.id)">
                                                <label for="{{$module['id']}}_delete">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="md-checkbox custom-padding">
                                                <input type="checkbox" class="md-check" data-role="{{$module['id']}}_print" data-module="{{$module['id']}}_print" id="{{$module['id']}}_print" onclick="checkPermission(this.id)">
                                                <label for="{{$module['id']}}_print">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="md-checkbox custom-padding">
                                                <input type="checkbox" class="md-check" data-role="{{$module['id']}}_upload" data-module="{{$module['id']}}_upload" id="{{$module['id']}}_upload" onclick="checkPermission(this.id)">
                                                <label for="{{$module['id']}}_upload">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    @foreach($permissions as $permission)
                                    @if(strtoupper($permission['module_type']) == strtoupper($module['description']) )
                                    <tr>
                                        <td>
                                            {{ strtoupper($permission['description']) }}
                                        </td>
                                        <td>
                                            <div class="md-checkbox">
                                                <input type="checkbox" class="md-check" data-role="{{$permission['id']}}_{{$module['id']}}_view" data-module="{{$permission['id']}}_{{$module['id']}}_view" id="{{$permission['id']}}_{{$module['id']}}_view" onchange="storeID(this.id)">
                                                <label for="{{$permission['id']}}_{{$module['id']}}_view">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="md-checkbox">
                                                <input type="checkbox" class="md-check" data-role="{{$permission['id']}}_{{$module['id']}}_create" data-module="{{$permission['id']}}_{{$module['id']}}_create" id="{{$permission['id']}}_{{$module['id']}}_create" onchange="storeID(this.id)">
                                                <label for="{{$permission['id']}}_{{$module['id']}}_create">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="md-checkbox">
                                                <input type="checkbox" class="md-check" data-role="{{$permission['id']}}_{{$module['id']}}_edit" data-module="{{$permission['id']}}_{{$module['id']}}_edit" id="{{$permission['id']}}_{{$module['id']}}_edit" onchange="storeID(this.id)">
                                                <label for="{{$permission['id']}}_{{$module['id']}}_edit">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="md-checkbox">
                                                <input type="checkbox" class="md-check" data-role="{{$permission['id']}}_{{$module['id']}}_delete" data-module="{{$permission['id']}}_{{$module['id']}}_delete" id="{{$permission['id']}}_{{$module['id']}}_delete" onchange="storeID(this.id)">
                                                <label for="{{$permission['id']}}_{{$module['id']}}_delete">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="md-checkbox">
                                                <input type="checkbox" class="md-check" data-role="{{$permission['id']}}_{{$module['id']}}_print" data-module="{{$permission['id']}}_{{$module['id']}}_print" id="{{$permission['id']}}_{{$module['id']}}_print" onchange="storeID(this.id)">
                                                <label for="{{$permission['id']}}_{{$module['id']}}_print">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="md-checkbox">
                                                <input type="checkbox" class="md-check" data-role="{{$permission['id']}}_{{$module['id']}}_upload" data-module="{{$permission['id']}}_{{$module['id']}}_upload" id="{{$permission['id']}}_{{$module['id']}}_upload" onchange="storeID(this.id)">
                                                <label for="{{$permission['id']}}_{{$module['id']}}_upload">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                    @endforeach

                                </tbody>
                            </table>

                        </div>

                </form>
            </div>
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
    $(document).ready(function() {
        getRolesPermissions($("#roleid").val());
        $("#roleid").on('change', function() {
            getRolesPermissions($("#roleid").val());

        })
    });

    function getRolesPermissions(roleid) {
        document.querySelectorAll('input[type=checkbox]').forEach(el => el.checked = false)
        $("#roles_permissions").val("");
        $.ajax({
            url: '{!! route('maintenance.roleaccessrights.store') !!}',
            type: 'get',
            
            data: {
                roleid: roleid
            },
            success: function(data) {
                $.each(data, function(index, element) {
                    var chkid = "";
                    chkid = (element.permission_id + "_" + element.module_id + "_" + element.action)
                    if (chkid != "") {
                        document.getElementById(element.module_id + "_" + element.action).checked = true;
                        document.getElementById(chkid).checked = true;

                        storeID(chkid);
                    }
                });
            }
        });
    }

    function checkPermission(id) {
        var checkboxes = document.getElementsByTagName("input");
        const cb = document.getElementById(id);
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type == "checkbox") {
                if (checkboxes[i].id.includes(id)) {
                    document.getElementById(checkboxes[i].id).checked = cb.checked;
                    storeID(checkboxes[i].id);
                }
            }
        }
    }

    function storeID(id) {
        var roles_permissions = $("#roles_permissions").val();
        
        if (document.getElementById(id).checked) {
            if (roles_permissions != "") {

                roles_permissions = roles_permissions + ',' + id;
            } else {

                roles_permissions = id;
            }
        } else {
        if((id.match(/_/g) || []).length == 2)
        {
                if (roles_permissions.includes("," + id)) {
                    roles_permissions = roles_permissions.replace("," + id, "");
                    console.log(roles_permissions);
                } else if (roles_permissions.includes(id + ",")) {
                    roles_permissions = roles_permissions.replace(id + ",", "");
                    
                    console.log(roles_permissions);
                } else {
                    roles_permissions = roles_permissions.replace(id, "");
                }
            }
        }
        $("#roles_permissions").val(roles_permissions);
    }
</script>

@endsection