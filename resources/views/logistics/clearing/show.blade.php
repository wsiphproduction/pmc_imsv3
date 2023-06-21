@extends('layouts.master-layout')
@section('title')Show Broker_Agent @endsection
@section('content')

<div class="row">
    <div class="col-xl-6 col-lg-6 col-12 m-auto">
        <a href="{{route('clearing.home')}}" class="btn btn-danger btn-sm float-right"> Back </a>
    </div>
</div>

    <div class="row mt-2">
        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 m-auto">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="card-title"> Crud</h5>
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <label for="title">Broker Agent <span class="text-danger">*</span> </label>
                        <input type="text" name="broker_agent" id="broker_agent" class="form-control" placeholder="Enter broker_agent" readonly value="@if(!empty($company)){{$company->broker_agent}}@else {{old('broker_agent')}} @endif" />
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection