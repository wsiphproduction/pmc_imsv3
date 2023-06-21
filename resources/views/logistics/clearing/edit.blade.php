
@extends('layouts.app')

@section('pagecss')
    <link href="{{env('APP_URL')}}/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
@endsection


{{-- @extends('layouts.master-layout') --}}
@section('title')Update Port Name @endsection
@section('content')


<div class="page-content">
<div class="row">
    <div class="col-xl-6 col-lg-6 col-12 m-auto">
        <a href="{{route('logistics.clearing.home')}}" class="btn btn-danger btn-sm float-right"> Back to Broker Agent List </a>
    </div>
</div>


<form action="{{route('company.update',$id)}}" method="post">
    @method("PUT")
    @csrf
    <div class="row mt-2">
        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 m-auto">
            <div class="card shadow">

                @if(Session::has('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        {{Session::get("success")}}
                    </div>

                @elseif(Session::has('failed'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        {{Session::get("failed")}}
                    </div>
                @endif

                <div class="card-header">
                    <h5 class="card-title">Edit Broker Agent</h5>
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <label for="title"> Broker Agent <span class="text-danger">*</span> </label>
                        <input type="text" name="broker_agent" id="broker_agent" class="form-control" placeholder="Enter broker agent" value="@if(!empty($company)){{$company->broker_agent}}@else {{old('broker_agent')}} @endif" />
                        {!!$errors->first('broker_agent', '<small class="text-danger"> :message </small>') !!}
                    </div>

                    {{-- <div class="form-group">
                        <label for="category"> Category <span class="text-danger">*</span> </label>
                        <input type="text" name="Category" id="Category" class="form-control" placeholder="Enter Category" value="@if(!empty($post)){{$post->Category}}@else{{old('Category')}} @endif" />
                        {!!$errors->first('category', '<small class="text-danger"> :message </small>') !!}
                    </div> --}}

                    {{-- <div class="form-group">
                        <label for="title"> Description <span class="text-danger">*</span> </label>
                        <textarea class="form-control" name="Description" placeholder="Enter Description">@if(!empty($post)){{$post->description}}@else{{old('Description')}}@endif</textarea>
                        {!!$errors->first('Description', '<small class="text-danger"> :message </small>') !!}
                    </div> --}}
                </div>

                <div class="card-footer">
                   <button type="submit" class="btn btn-success">Update</button>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
@endsection



@section('pagejs')
<script src="{{env('APP_URL')}}/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>

@endsection