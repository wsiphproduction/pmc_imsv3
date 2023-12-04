@extends('layouts.app')

@section('pagecss')
    <link href="{{env('APP_URL')}}/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
    @endsection

@section('title')Post Listing @endsection
@section('content')

<div class="page-content">
    <div class="container">

        {{-- <div class="row">
            <div class="col-md-12">
                <a href="{{ url('http://172.16.20.28/PMC-IMS_V3-latest/public/ims/logistics/shipment-waybills') }}" class="btn btn-default"><i class="fa fa-backward"></i> Back</a>
            </div>   
        </div>
        <br> --}}

        <div class="row mb-2">
            <div class="col-xl-12 col-lg-12 col-12 m-auto">
                <a href="{{route('post.create')}}" class="btn btn-primary btn-sm float-right"> Add New Port Name</a>
            </div>
        </div>

        </div>
        <br>

        {{-- <div class="form-inline pull-right">
            <div class="input-group">
              <input type="text" name="search" id="search" placeholder="Search..." class="form-control">
              <span class="input-group-btn">
                <button class="btn btn-primary"><i class="fa fa-search"></i></button>
              </span> 
            </div>
        </div> --}}

        <table class="table table-striped">
            <thead>
                <tr>
                    <th> ID </th>
                    <th> Portname </th>
                     {{-- <th> Category </th>
                    <th> Description </th> --}}
                    <th> Created on </th>
                    {{-- <th> Status </th> --}}
                    <th> Action </th>
                </tr>
            </thead>
            <tbody>
                @if(!@empty($posts))
                    @foreach($posts as $post)
                        <tr>
                            <td> {{$post->id}} </td>
                            <td> {{$post->Portname}} </td>
                            {{-- <td> {{$post->Category}} </td>
                            <td> {{$post->Description}} </td> --}}
                            <td> {{$post->created_at}} </td>
                            {{-- <td> @if($post->published == 1) <span class="badge badge-success">Published</span> @else NA @endif </td> --}}
                            <td>
                                <form action="{{route('post.destroy', $post->id)}}" method="post">
                                {{-- <a href="{{route('post.show', $post->id)}}" class="btn btn-info btn-sm"> View </a> --}}
                                <a href="{{route('post.edit', $post->id)}}" class="btn btn-success btn-sm"> Edit </a>
                                    
                                @csrf
                                @method("DELETE")
                                    {{-- <button type="submit" class="first btn btn-danger btn-sm" onclick="alert('confirmed');">Delete</button> --}}

                                    <!-- Button trigger modal -->
                            <button type="submit" class="btn btn-danger" onclick="return confirm('are your sure?')" data-mdb-toggle="modal" data-mdb-target="#exampleModal">
                                Delete
                            </button>
                            
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">...</div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Save changes</button>
                                    </div>
                                </div>
                                </div>
                            </div>
                           
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        
    {{-- Start Pagination --}}
    <div class="row">
        <div class="col-md-6">
            <p>Showing {{$c->firstItem()}} to {{$c->lastItem()}} of {{$c->total()}} items</p>
        </div>
        <div class="col-md-6">
            <span class="pull-right">{{ $c->appends($param)->links() }}</span>
        </div>
    </div>
{{-- End Pagination --}}

    </div>
</div>
@endsection


@section('pagejs')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
@endsection

{{-- <script type="text/javascript">
    jQuery(document).ready(function() {
        alert("hehehe");
    });
</script> --}}
