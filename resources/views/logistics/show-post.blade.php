@extends('layouts.master-layout')
@section('title')Show Port @endsection
@section('content')

<div class="row">
    <div class="col-xl-6 col-lg-6 col-12 m-auto">
        <a href="{{route('logistics.index')}}" class="btn btn-danger btn-sm float-right"> Back to Dash </a>
    </div>
</div>

    <div class="row mt-2">
        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 m-auto">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="card-title"> Show Port Name</h5>
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <label for="title"> Port Name <span class="text-danger">*</span> </label>
                        <input type="text" name="Portname" id="Portname" class="form-control" placeholder="Enter Portname" readonly value="@if(!empty($post)){{$post->Portname}}@else {{old('Portname')}} @endif" />
                    </div>

                    {{-- <div class="form-group">
                        <label for="category"> Category <span class="text-danger">*</span> </label>
                        <input type="text" name="Category" id="Category" class="form-control" placeholder="Enter Category" readonly value="@if(!empty($post)){{$post->Category}}@else{{old('Category')}} @endif" />
                    </div> --}}

                    {{-- <div class="form-group">
                        <label for="title"> Description <span class="text-danger">*</span> </label>
                        <textarea class="form-control" name="Description" readonly placeholder="Enter Description">@if(!empty($post)){{$post->Description}}@else{{old('Description')}}@endif</textarea>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection