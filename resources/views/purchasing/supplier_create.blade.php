@extends('layouts.app')

@section('pagecss')
<link href="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
<link href="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="page-content">
    <!-- BEGIN BREADCRUMBS -->
    <div class="breadcrumbs">
        <ol class="breadcrumb">
            <li>
                <!-- <a href="/dashboard">Dashboard</a> -->
                <a href="{{ route('ims.dashboard') }}">Dashboard</a>
            </li>
            <li>
                <a href="{{env('APP_URL')}}/ims/purchasing">Purchasing</a>
            </li>
            <li class="active">Suppliers</li>
        </ol>
    </div>
    <!-- END BREADCRUMBS -->
    
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-red-sunglo">
                        <i class="icon-settings font-red-sunglo"></i>
                        <span class="caption-subject bold uppercase"> Default Form</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <form autocomplete="off" action="{{ route('supplier.store') }}" method="post" class="horizontal-form">
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Supplier Code <i class="text-danger">*</i></label>
                                        <input name="code" type="text" class="form-control input-lg" required maxlength="20" placeholder="Supplier Code">
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label>Name <i class="text-danger">*</i></label>
                                        <input name="name" type="text" class="form-control input-lg" required maxlength="30" placeholder="Name">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Contact <i class="text-danger">*</i></label>
                                        <input type="text" name="contact" class="form-control input-lg" required maxlength="30" placeholder="Tel # / CP # / Email">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Contact Person <i class="text-danger">*</i></label>
                                        <input type="text" name="contact_person" class="form-control input-lg" required maxlength="30" placeholder="Name">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>LTO Validity <i class="text-danger">*</i></label>
                                        <input type="text" name="lto" class="form-control input-lg" required maxlength="30" placeholder="LTO Validity">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Address <i class="text-danger">*</i></label>
                                        <textarea name="address" class="form-control input-lg" rows="3" required maxlength="200" placeholder="Address"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions right">
                            <a href="{{ route('supplier.index') }}" class="btn btn-default">Cancel</a>
                            <button type="submit" class="btn blue">
                                <i class="fa fa-check"></i> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
    <script src="{{env('APP_URL')}}/assets/global/scripts/datatable.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
    <script src="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
@endsection