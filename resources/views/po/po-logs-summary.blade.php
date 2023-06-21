@extends('layouts.app')

@section('pagecss')
<link href="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
<link href="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
<link href="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
<link href="{{env('APP_URL')}}/assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />

<link href="{{env('APP_URL')}}/assets/global/plugins/bootstrap-sweetalert/sweetalert.css" rel="stylesheet" type="text/css" />
<link href="{{env('APP_URL')}}/login/css/main1.css" rel="stylesheet" />

<style type="text/css">
    #image_preview{
        padding: 10px;
    }

    #image_preview img{
        width: 250px;
        padding: 5px;
    }
    .task-content p { font-size: 11px !important; font-family: monospace; }
    .task-content h6 { font-size: 12px !important; font-family: monospace;}
</style>
@endsection

@section('content')
<div class="page-container">
    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <!-- BEGIN CONTENT BODY -->
        <!-- BEGIN PAGE CONTENT BODY -->
        <div class="page-content">
            <div class="container-fluid">
                <!-- BEGIN PAGE CONTENT INNER -->
                <div class="page-content-inner">
                    <div class="row">

                        <div class="col-lg-3">
                            <div class="portlet light bordered">
                                <div class="portlet-body">
                                    <div class="task-content">
                                        <div class="scroller" style="height: 730px;" data-always-visible="1" data-rail-visible1="1">
                                            <div class="mt-element-list">
                                                <div class="mt-list-container list-todo" role="tablist">
                                                    <div class="list-todo-line"></div>
                                                    <ul>
                                                        <li class="mt-list-item">
                                                            <div class="list-todo-item green-soft">
                                                                <a class="list-toggle-container" data-toggle="collapse" data-parent="#accordion1" onclick=" " href="#task-1" aria-expanded="false">
                                                                    @foreach($poc as $pc)
                                                                        <div class="list-toggle done uppercase">
                                                                            <div class="list-toggle-title bold"><i class="fa fa-database"></i> PO # {{ $pc->poNumber }}</div>
                                                                                <div class="list-head-count-item">
                                                                                    <i class="fa fa-calendar-check-o"></i> Created : {{ $pc->addedDate }}
                                                                                </div>
                                                                                <div class="list-head-count-item">
                                                                                    <i class="fa fa-user"></i> User : {{ $pc->addedBy }}
                                                                                </div>
                                                                        </div>
                                                                    @endforeach
                                                                </a>
                                                                <div class="task-list panel-collapse collapse in" id="task-1">
                                                                    <ul>
                                                                        @foreach($po as $p)
                                                                            <li class="task-list-item done">
                                                                                <div class="task-icon">
                                                                                    <a href="javascript:;">
                                                                                        <i class="fa fa-calendar-check-o"></i>
                                                                                    </a>
                                                                                </div>
                                                                                <div class="task-status">
                                                                                    <a class="done" href="javascript:;">
                                                                                        <i class="fa fa-pencil"></i>
                                                                                    </a>
                                                                                </div>
                                                                                <div class="task-content">
                                                                                    <h6 class="uppercase bold">
                                                                                        <a href="javascript:;">{{ $p->log_date }}</a>
                                                                                    </h6>
                                                                                    <p>By: {{ $p->users }}</p>
                                                                                    <p>Field: {{ $p->affected_field }}</p>
                                                                                    <p>From: {{ $p->old_value }}</p>
                                                                                    <p>To: {{ $p->new_value }}</p>
                                                                                </div>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="portlet light bordered">
                                <div class="portlet-body">
                                    <div class="task-content">
                                        <div class="scroller" style="height: 730px;" data-always-visible="1" data-rail-visible1="1">
                                            
                                            <div class="mt-element-list">
                                                <div class="mt-list-container list-todo" role="tablist">
                                                    <div class="list-todo-line"></div>
                                                    <ul>
                                                        <li class="mt-list-item">
                                                            <div class="list-todo-item blue-soft">
                                                                <a class="list-toggle-container" data-toggle="collapse" data-parent="#accordion1" onclick=" " href="#task-1" aria-expanded="false">
                                                                    <div class="list-toggle done uppercase">
                                                                        <div class="list-toggle-title bold"><i class="fa fa-database"></i> Finance / Accounting</div>
                                                                    </div>
                                                                </a>
                                                                <div class="task-list panel-collapse collapse in" id="task-1">
                                                                    <ul>
                                                                        @foreach($poa as $pa)
                                                                            <li class="task-list-item done">
                                                                                <div class="task-icon">
                                                                                    <a href="javascript:;">
                                                                                        <i class="fa fa-calendar-check-o"></i>
                                                                                    </a>
                                                                                </div>
                                                                                <div class="task-status">
                                                                                    <a class="done" href="javascript:;">
                                                                                        <i class="fa fa-pencil"></i>
                                                                                    </a>
                                                                                </div>
                                                                                <div class="task-content">
                                                                                    <h6 class="uppercase bold">
                                                                                        <a href="javascript:;">{{ $pa->log_date }}</a>
                                                                                    </h6>
                                                                                    <p>By: {{ $pa->users }}</p>
                                                                                    <p>Field: {{ $pa->affected_field }}</p>
                                                                                    <p>From: {{ $pa->old_value }} - To: {{ $pa->new_value }}</p>
                                                                                </div>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="portlet light bordered">
                                <div class="portlet-body">
                                    <div class="task-content">
                                        <div class="scroller" style="height: 730px;" data-always-visible="1" data-rail-visible1="1">
                                            
                                            <div class="mt-element-list">
                                                <div class="mt-list-container list-todo" role="tablist">
                                                    <div class="list-todo-line"></div>
                                                    <ul>
                                                        <li class="mt-list-item">
                                                            <div class="list-todo-item yellow">
                                                                <a class="list-toggle-container" data-toggle="collapse" data-parent="#accordion1" onclick=" " href="#task-1" aria-expanded="false">
                                                                    <div class="list-toggle done uppercase">
                                                                        <div class="list-toggle-title bold"><i class="fa fa-database"></i>Logistics</div>
                                                                    </div>
                                                                </a>
                                                                <div class="task-list panel-collapse collapse in" id="task-1">
                                                                    <ul>
                                                                        @foreach($pol as $pl)
                                                                            <li class="task-list-item done">
                                                                                @if($pl->action == 'INSERT')
                                                                                    <div class="task-icon">
                                                                                    <a href="javascript:;">
                                                                                        <i class="fa fa-database"></i>
                                                                                    </a>
                                                                                    </div>
                                                                                    <div class="task-status">
                                                                                        <a class="done" href="javascript:;">
                                                                                            <i class="fa fa-plus"></i>
                                                                                        </a>
                                                                                    </div>
                                                                                    <div class="task-content">
                                                                                        <h6 class="uppercase bold">
                                                                                            <a href="javascript:;">{{ $pl->log_date }}</a>
                                                                                        </h6>
                                                                                        <p>Waybill : {{ $pl->new_value }}</p>
                                                                                        <p>Added By : {{ $pl->users }}</p>  
                                                                                    </div>
                                                                                @endif


                                                                                @if($pl->action == 'UPDATE')
                                                                                    <div class="task-icon">
                                                                                        <a href="javascript:;">
                                                                                            <i class="fa fa-calendar-check-o"></i>
                                                                                        </a>
                                                                                    </div>
                                                                                    <div class="task-status">
                                                                                        <a class="done" href="javascript:;">
                                                                                            <i class="fa fa-pencil"></i>
                                                                                        </a>
                                                                                        </div>
                                                                                    <div class="task-content">
                                                                                        <h6 class="uppercase bold">
                                                                                            <a href="javascript:;">{{ $pl->log_date }}</a>
                                                                                        </h6>
                                                                                        <p>By: {{ $pl->users }}</p>
                                                                                        <p>Field: {{ $pl->affected_field }}</p>
                                                                                        <p>From: {{ $pl->old_value }} - To: {{ $pl->new_value }}</p>
                                                                                    </div>
                                                                                @endif
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="portlet light bordered">
                                <div class="portlet-body">
                                    <div class="task-content">
                                        <div class="scroller" style="height: 730px;" data-always-visible="1" data-rail-visible1="1">
                                            
                                            <div class="mt-element-list">
                                                <div class="mt-list-container list-todo" role="tablist">
                                                    <div class="list-todo-line"></div>
                                                    <ul>
                                                        <li class="mt-list-item">
                                                            <div class="list-todo-item red-soft">
                                                                <a class="list-toggle-container" data-toggle="collapse" data-parent="#accordion1" onclick=" " href="#task-1" aria-expanded="false">
                                                                    <div class="list-toggle done uppercase">
                                                                        <div class="list-toggle-title bold"><i class="fa fa-database"></i> MCD</div>
                                                                    </div>
                                                                </a>
                                                                <div class="task-list panel-collapse collapse in" id="task-1">
                                                                    <ul>
                                                                        @foreach($pod as $pd)
                                                                            <li class="task-list-item done">
                                                                                @if($pd->action == 'INSERT')
                                                                                    <div class="task-icon">
                                                                                    <a href="javascript:;">
                                                                                        <i class="fa fa-database"></i>
                                                                                    </a>
                                                                                    </div>
                                                                                    <div class="task-status">
                                                                                        <a class="done" href="javascript:;">
                                                                                            <i class="fa fa-plus"></i>
                                                                                        </a>
                                                                                    </div>
                                                                                    <div class="task-content">
                                                                                        <h6 class="uppercase bold">
                                                                                            <a href="javascript:;">{{ $pd->log_date }}</a>
                                                                                        </h6>
                                                                                        <p>DRR : {{ $pd->new_value }}</p>
                                                                                        <p>Added By : {{ $pd->users }}</p>
                                                                                    </div>
                                                                                @endif


                                                                                @if($pd->action == 'UPDATE')
                                                                                    <div class="task-icon">
                                                                                        <a href="javascript:;">
                                                                                            <i class="fa fa-calendar-check-o"></i>
                                                                                        </a>
                                                                                    </div>
                                                                                    <div class="task-status">
                                                                                        <a class="done" href="javascript:;">
                                                                                            <i class="fa fa-pencil"></i>
                                                                                        </a>
                                                                                    </div>
                                                                                    <div class="task-content">
                                                                                        <h6 class="uppercase bold">
                                                                                            <a href="javascript:;">{{ $pd->log_date }}</a>
                                                                                        </h6>
                                                                                        <p>By: {{ $pd->users }}</p>
                                                                                        <p>Field: {{ $pd->affected_field }}</p>
                                                                                        <p>From: {{ $pd->old_value }} - To: {{ $pd->new_value }}</p>
                                                                                    </div>
                                                                                @endif
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@include('modal.payments-modal')
</div>
@endsection

@section('pagejs')
<script src="{{env('APP_URL')}}/assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js}" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/pages/scripts/table-datatables-buttons.min.js" type="text/javascript"></script>


@endsection