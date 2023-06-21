@extends('layouts.master-layout')
@section('title')Create New Post @endsection
@section('content')


<div class="row">
    <div class="col-xl-6 col-lg-6 col-12 m-auto">
        <a href="{{route('logistics.index')}}" class="btn btn-danger btn-sm float-right"> Back to Posts </a>
    </div>
</div>

<form action="{{route('post.store')}}" method="post">
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
                    <h5 class="card-title">Create New Port Name</h5>
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <label for="title">Port Name <span class="text-danger">*</span> </label>
                        <input type="text" name="Portname" id="Portname" class="form-control" placeholder="Enter Portname" value="{{old('Portname')}}" required/>
                        {!!$errors->first('port', '<small class="text-danger"> :message </small>') !!}
                    </div>

                    {{-- <div class="form-group">
                        <label for="category"> Category <span class="text-danger">*</span> </label>
                        <input type="text" name="Category" id="category" class="form-control" placeholder="Enter Category" value="{{old('Category')}}" required/>
                        {!!$errors->first('Category', '<small class="text-danger"> :message </small>') !!}
                    </div> --}}

                    {{-- <div class="form-group">
                        <label for="title"> Description <span class="text-danger">*</span> </label>
                        <textarea class="form-control" name="Description" placeholder="Enter Description">{{old('description')}}</textarea>
                        {!!$errors->first('Description', '<small class="text-danger"> :message </small>') !!}
                    </div> --}}
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success">Publish Post</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection