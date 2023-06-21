@extends('layouts.app')

@section('pagecss')
<link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />

<style>
    .b-price-plan__item{
        padding: 30px 30px; 
    }
    .b-price-plan__cost{    
        font-size: 20px;
        font-weight: 600;
        position: relative;
        z-index: 99;
        text-align: center; 
        background: rgba(125,138,164,.1);
    }
    .cost-title{
        font-size: 45px;
        line-height: 1;
        font-weight: 700;   
        color: rgba(125,138,164,1);
    }

    .best-plan__title{
        font-size: 18px;
        margin-bottom: 15px;
        margin-top:50px;
        font-weight: 800;
        color:#3c2f17;
    }

    .best-plan__month{
        font-size: 18px;
        margin-bottom: 15px;
        margin-top:30px;
        font-weight: 800;
        color:white;
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <!-- BEGIN BREADCRUMBS -->
    <div class="breadcrumbs">
        <h1>Dashboard</h1>
    </div>
    <!-- END BREADCRUMBS -->

    <div class="row widget-row">
        <div class="col-md-4">
        	<a href="{{ route('overdue.payables') }}">
        		<div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
	                <h4 class="widget-thumb-heading">Overdue Payables</h4>
	                <div class="widget-thumb-wrap">
	                    <i class="widget-thumb-icon bg-red icon-layers"></i>
	                    <div class="widget-thumb-body">
	                        <span class="widget-thumb-body-stat" data-counter="counterup" data-value="{{ \App\PaymentSchedule::totalOverduePayables() }}">0</span>
	                    </div>
	                </div>
	            </div>
        	</a>
        </div>
        <div class="col-md-4">
        	<a href="{{ route('overdue.completion') }}">
        		<div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
	                <h4 class="widget-thumb-heading">Overdue Completion</h4>
	                <div class="widget-thumb-wrap">
	                    <i class="widget-thumb-icon bg-purple icon-screen-desktop"></i>
	                    <div class="widget-thumb-body">
	                        <span class="widget-thumb-body-stat" data-counter="counterup" data-value="{{ \App\PO::totalOverduePO() }}">0</span>
	                    </div>
	                </div>
	            </div>
        	</a>
        </div>
        <div class="col-md-4">
        	<a href="{{ route('po.status') }}">
				<div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
	                <h4 class="widget-thumb-heading">Total Open PO</h4>
	                <div class="widget-thumb-wrap">
	                    <i class="widget-thumb-icon bg-blue icon-bar-chart"></i>
	                    <div class="widget-thumb-body">
	                        <span class="widget-thumb-body-stat" data-counter="counterup" data-value="{{ \App\PO::totalOpenPO() }}">0</span>
	                    </div>
	                </div>
	            </div>
        	</a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-6 col-xs-12 col-sm-12">
            <div class="portlet light tasks-widget bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-share font-dark hide"></i>
                        <span class="caption-subject font-dark bold uppercase">PO Completion</span>
                        <span class="caption-helper text-uppercase">{{ date('F Y') }}</span>
                    </div>
                    <div class="actions">
                        <div class="btn-group">
                            <span class="badge badge-info">{{ \App\PO::totalCompletionOfTheMonth() }}</span>
                        </div>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="task-content">
                        <div class="scroller" style="height: 400px;" data-always-visible="1" data-rail-visible1="1">
                            <div class="mt-comments">
                                @forelse($monthly_completion as $data)
                                    <div class="mt-comment">
                                        <div class="mt-comment-body">
                                            <div class="mt-comment-info">
                                                <span class="mt-comment-author">PO #{{ $data->poNumber }}</span>
                                                <span class="mt-comment-date">{{ date('F d Y',strtotime($data->expectedCompletionDate)) }}</span>
                                            </div>
                                            <div class="mt-comment-text">{{ $data->supplier_name->name }}</div>
                                            <div class="mt-comment-details">
                                                <ul class="mt-comment-actions">
                                                    <li>
                                                        <a href="{{ route('po.details',$data->id) }}" target="_blank">View</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @empty

                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-xs-12 col-sm-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-share font-dark hide"></i>
                        <span class="caption-subject font-dark bold uppercase">Recent Activities</span>
                    </div>
                </div>
                {{-- <div class="portlet-body">
                    <div class="scroller" style="height: 400px;" data-always-visible="1" data-rail-visible="0">
                        @foreach($activities as $act)
                            <div class="mt-actions">
                                <div class="mt-action">
                                    <div class="mt-action-img">
                                        <img height="45px" src="{{ asset('images/user.png') }}"/> </div>
                                    <div class="mt-action-body">
                                        <div class="mt-action-row">
                                            <div class="mt-action-info ">
                                                <div class="mt-action-details ">
                                                    <span class="mt-action-author">{{ strtoupper($act->userdetails->name) }}</span>
                                                    <p class="mt-action-desc"><a href="{{route('po.details',$act->poId)}}" target="_blank">PO#{{ $act->podetails->poNumber }}</a> : {{ $act->log_desc }}</p>
                                                </div>
                                            </div>
                                            <div class="mt-action-datetime ">
                                                <span class="mt=action-time">{{ \App\AuditLogs::date_for_listing($act->log_date) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </div><!-- 
                        <div class="scroller-footer">
                            <div class="btn-arrow-link pull-right">
                                <a href="javascript:;">See All Activities</a>
                                <i class="icon-arrow-right"></i>
                            </div>
                        </div> -->
                </div> --}}
            </div>
        </div>
    </div>
</div>
@include('modal.payments')
@endsection

@section('pagejs')
<script src="{{ asset('assets/global/plugins/morris/morris.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/morris/raphael-min.js') }}" type="text/javascript"></script>

<script src="{{ asset('assets/global/plugins/amcharts/amcharts/amcharts.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/amcharts/amcharts/serial.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/amcharts/amcharts/pie.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/amcharts/amcharts/radar.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/amcharts/amcharts/themes/light.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/amcharts/amcharts/themes/patterns.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/amcharts/amcharts/themes/chalk.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/amcharts/ammap/ammap.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/amcharts/ammap/maps/js/worldLow.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/amcharts/amstockcharts/amstock.js') }}" type="text/javascript"></script>

<script src="{{ asset('assets/pages/scripts/dashboard.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
@endsection


