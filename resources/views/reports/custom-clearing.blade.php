@extends('layouts.app')

@section('pagecss')
<link href="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
<link href="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
<link href="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />

<link href="{{env('APP_URL')}}/assets/global/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
<link href="{{env('APP_URL')}}/login/css/main1.css" rel="stylesheet" />

<style type="text/css">
td {
    font-size: 12px !important;
}
</style>
@endsection

@section('content')
<div class="page-container">
    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <!-- BEGIN PAGE CONTENT BODY -->
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="s01">
                        <form>
                            <div class="inner-form">
                                <div class="input-field first-wrap">
                                    <input class="form-control date-picker" size="16" type="text" value="" placeholder="From" />
                                </div>
                                <div class="input-field first-wrap">
                                    <input class="form-control date-picker" size="16" type="text" value="" placeholder="To" />
                                </div>
                                <div class="input-field third-wrap">
                                    <button class="btn-search" type="button"><i class="fa fa-filter"></i> Generate</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- BEGIN PAGE CONTENT INNER -->
                <div class="page-content-inner">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN SAMPLE TABLE PORTLET-->
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-calendar font-blue-hoki"></i>
                                        <span class="caption-subject font-blue-hoki bold uppercase">Custom Clearing</span>
                                    </div>
                                    <button class="btn green pull-right"><i class="fa fa-file-excel-o"></i> Export to Excel</button>
                                </div>
                                <div class="portlet-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                                                    <div class="portlet light bordered">
                                                        <div class="portlet-title">
                                                            <div class="caption font-green">
                                                                <i class="fa fa-tag font-green"></i>
                                                                <span class="caption-subject bold uppercase"> PO Aging</span>
                                                            </div>
                                                        </div>
                                                        <div class="portlet-body">
                                                            <table class="table table-striped table-bordered table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th>PO</th>
                                                                        <th>Supplier</th>
                                                                        <th>First Payment Date</th>
                                                                        <th>Total Days</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>    
                                                                    <tr>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>124</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>124</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>124</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>124</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>124</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>124</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>124</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>124</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>124</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>124</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>124</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>124</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>124</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>124</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>124</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>124</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>124</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>124</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>124</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>124</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>124</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>124</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>124</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>124</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>124</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>124</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>124</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>124</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>12</td>
                                                                        <td>124</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                                                                                                                                                  
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- END SAMPLE TABLE PORTLET-->
                        </div>
                    </div>
                </div>
                <!-- END PAGE CONTENT INNER -->
            </div>
        </div>
        <!-- END PAGE CONTENT BODY -->
    </div>
    <!-- END CONTENT -->
</div>
@endsection

@section('pagejs')
<script src="{{env('APP_URL')}}/assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/pages/scripts/table-datatables-buttons.min.js" type="text/javascript"></script>

<script src="{{env('APP_URL')}}/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>

<script src="{{env('APP_URL')}}/assets/global/plugins/morris/morris.min.js" type="text/javascript"></script>
<script src="{{env('APP_URL')}}/assets/global/plugins/morris/raphael-min.js" type="text/javascript"></script>
@endsection