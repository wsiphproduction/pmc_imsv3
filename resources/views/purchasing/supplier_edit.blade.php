@extends('layouts.app')

@section('content')
<div class="page-content">
    <!-- BEGIN BREADCRUMBS -->
    <div class="breadcrumbs">
        <h1>Update Supplier</h1>
        <ol class="breadcrumb">
            <li>
                <!-- <a href="/dashboard">Dashboard</a> -->
                <a href="{{ route('ims.dashboard') }}">Dashboard</a>
            </li>
            <li>
                <a href="{{env('APP_URL')}}/ims/purchasing">Purchasing</a>
            </li>
            <li class="active">Update Supplier</li>
        </ol>
    </div>
    <!-- END BREADCRUMBS -->
                    
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <form autocomplete="off" action="{{ route('supplier.update') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{$supplier->id}}">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-ship font-red-sunglo"></i>
                            <span class="caption-subject font-red-sunglo bold uppercase">Supplier Form</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="form-body">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Supplier Code <i class="font-red">*</i></label>
                                        <input type="text" name="code" class="form-control input-lg" value="{{$supplier->Supplier_Code}}" required maxlength="20" placeholder="Supplier Code">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Name <i class="font-red">*</i></label>
                                        <textarea name="name" id="" cols="30" rows="1" class="form-control input-lg" required maxlength="30" placeholder="Name">{{$supplier->name}}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Contact (Tel # / Cp # / Email) <i class="font-red">*</i></label>
                                        <input type="text" class="form-control input-lg" name="contact" value="{{$supplier->contact}}" required maxlength="30" placeholder="Tel # / CP # / Email">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Contact Person <i class="font-red">*</i></label>
                                        <input type="text" class="form-control input-lg" name="contact_person" value="{{$supplier->Contact_Person}}" equired maxlength="30" placeholder="Name">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>LTO Validity</label>
                                        <input type="text" class="form-control input-lg" name="lto" value="{{$supplier->LTO_validity}}" required maxlength="30" placeholder="LTO Validity">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Address <i class="font-red">*</i></label>
                                        <textarea name="address" id="" cols="30" rows="2" class="form-control input-lg" required maxlength="200" placeholder="Address">{{$supplier->address}}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn blue pull-right"><i class="fa fa-save"></i> Save Changes</button>
                                    <a href="{{env('APP_URL')}}/ims/supplier" style="margin-right: 5px;" class="btn btn-default pull-right">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  
            </form>
        </div>
        <div class="col-md-2"></div>
    </div>
</div>
@endsection

@section('pagejs')
<script type="text/javascript"src="{{env('APP_URL')}}/assets/global/plugins/select2/js/select2.full.min.js"></script>
<script type="text/javascript"src="{{env('APP_URL')}}/assets/pages/scripts/components-select2.min.js"></script>

<script type="text/javascript">
    $("#uploadFile").change(function(){
        $('#file_preview').html("");

        var total_file=document.getElementById("uploadFile").files.length;

        for(var i=0;i<total_file;i++){
            $('#file_preview').append("<img src='"+URL.createObjectURL(event.target.files[i])+"'>");
        }

    });
</script>
@endsection