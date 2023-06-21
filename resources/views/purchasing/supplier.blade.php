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
            <div class="portlet light portlet-fit bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-settings font-red"></i>
                        <span class="caption-subject font-red sbold uppercase">Suppliers</span>
                        @if($create)
                        <a href="{{ route('supplier.create') }}" class="btn btn-success pull-right">Add a Supplier</a>
                        @else
                        <button disabled href="{{ route('supplier.create') }}" class="btn btn-success pull-right">Add a Supplier</button>
                        @endif
                        <div class="col-md-3 pull-right">
                            <div class="form-group">
                                <form autocomplete="off" id="form">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" id="supplier" class="form-control" placeholder="Search supplier name">
                                            <span class="input-group-btn">
                                                <button class="btn blue" type="submit">Search</button>
                                            </span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-scrollable">
                        <table class="table table-hover table-bordered table-advance">
                            <thead>
                                <tr class="uppercase">
                                    <th>#</th>
                                    <th>Supplier Code</th>
                                    <th>Name</th>
                                    <th>Contact #</th>
                                    <th>Address</th>
                                    <th>Contact Person</th>
                                    <th>LTO Validity</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($collection as $supplier)
                                    <tr>
                                        <td>{{ ($collection->currentpage()-1) * $collection->perpage() + $loop->index + 1 }}</td>
                                        <td class="text-uppercase">{{$supplier->Supplier_Code}}</td>
                                        <td class="text-uppercase">{{$supplier->name}}</td>
                                        <td>{{$supplier->contact}}</td>
                                        <td class="text-uppercase">{{$supplier->address}}</td>
                                        <td>{{$supplier->Contact_Person}}</td>
                                        <td>{{$supplier->LTO_validity}}</td>
                                        <td>
                                        @if($edit)
                                            <a href="{{env('APP_URL')}}/ims/supplier/edit/{{$supplier->id}}" class="btn btn-sm blue">Edit</a                                           
                                            @else
                                            <button disabled href="/ims/supplier/edit/{{$supplier->id}}" class="btn btn-sm blue">Edit</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <p>Showing {{$collection->firstItem()}} to {{$collection->lastItem()}} of {{$collection->total()}} items</p>
                </div>
                <div class="col-md-6">
                    <span class="pull-right">{{ $collection->appends($param)->links() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@include('modal.payments')
@endsection

@section('pagejs')
<script src="{{env('APP_URL')}}/assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>

<script>
    /*** handles the click function on filter classes, redirects it to function filter ***/
    $('#form').submit(function(e){
        e.preventDefault();

        filter();
    });

    /*** generate the parameters for filtering of pages ***/
    function filter(){

        var url = '';
        url = 'supplier='+$('#supplier').val();

        window.location.href = "{{ route('search.supplier') }}?"+url;
    }
</script>
@endsection