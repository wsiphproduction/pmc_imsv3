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
        <h1>Recipients</h1>
        <ol class="breadcrumb">
            <li>
                <!-- <a href="/dashboard">Dashboard</a> -->
                <a href="{{ route('ims.dashboard') }}">Dashboard</a>
            </li>
            <li>
                <a href="javascript:;">Settings</a>
            </li>
            <li class="active">Email Recipients</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-title" id="title">
                    <div class="caption">
                        <i class="fa fa-list font-blue-hoki"></i>
                        <span class="caption-subject font-blue-hoki bold uppercase"> List Of Recipients</span>
                    </div>
                    @if($create)
                    <a href="{{ route('email-recipients.create') }}" class="btn btn-info pull-right">Add Recipient</a>
                    @else
                    <button disabled href="{{ route('email-recipients.create') }}" class="btn btn-info pull-right">Add Recipient</button>
                    @endif
                </div>
                <br>
                <table class="user_tbl table table-hover">
                    <thead>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @forelse($recipients as $recipient)
                            <tr>
                                <td> {{$recipient->name}} </td>
                                <td> {{$recipient->email}} </td>
                                <td> 
                                    @if($edit)
                                    <a href="{{ route('email-recipients.edit', $recipient->id) }}" class="btn btn-primary"> Edit </a>
                                    @else
                                    <button disabled href="{{ route('email-recipients.edit', $recipient->id) }}" class="btn btn-primary"> Edit </button>
                                    @endif
                                    @if($delete)
                                    <a href="#" id="deleteBtn" data-id="{{ $recipient->id }}" class="btn btn-danger"> Delete </a>  
                                    @else
                                    <button disabled href="#" id="deleteBtn" data-id="{{ $recipient->id }}" class="btn btn-danger"> Delete </button>  
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center"> No Recipient Found </td></tr>
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
<script>

    $(document).on('click', '#deleteBtn', function (e) {
        e.preventDefault();
        var id = $(this).data('id');

        Swal.fire({
            title: "Are you sure?",
            text: 'This recipient will be removed permanently!',
            confirmButtonText: "Remove",
            showCancelButton: true
        }).then(
        function(result) {
            if(result.isConfirmed){
                $.ajax({
                    type: "DELETE",
                    url: "{{env('APP_URL')}}/ims/email-recipients/"+id,
                    data: {
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                        'id':id
                    },
                    success: function (data) {
                        console.log(data);
                        new swal({
                            title: "Deleted",
                            text: 'Recipient has been deleted permanently.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        window.setTimeout(function(){location.reload()},1000)
                    }         
                });
            }
        });
    });
</script>
@endsection