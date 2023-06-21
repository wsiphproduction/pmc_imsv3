<!-- <div id="editDetails" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Update PO Details
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <form method="post" action="/updatePO" class="form-horizontal" role="form">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-sm-9">
                            <input type="hidden" name="id"  class="form-control" value="{{$data[0]['id']}}">
                            <input type="hidden" name="pon" class="form-control" value="{{$data[0]['poNumber']}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="dt">Date Order :</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="order" value="{{ $data[0]['orderDate'] }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="dt">Supplier's Completion Date :</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="completion" value="{{ $data[0]['expectedCompletionDate'] }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="dt">Expected Delivery Date :</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="ex_delivery" value="{{ $data[0]['expectedDeliveryDate'] }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="dt">Supplier :</label>
                        <div class="col-sm-9">
                            <select data-live-search="true" data-live-search-style="startsWith" class="selectpicker form-control" name="supplier" id="supplier">
                                @foreach($supplier as $d)
                                    <option @if($data[0]['supplier'] == $d->name) selected @endif value="{{ $d->id }}">{{ $d->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="amt">Item Commodity :</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="commodity" value="{{ $data[0]['itemCommodity'] }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="amt">PO Amount :</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="amounts" value="{{ $data[0]['amount'] }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="amt">PO Qty :</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="qtys" value="{{ $data[0]['qty'] }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="amt">Currency :</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="currency" id="currency">
                                <option @if($data[0]['currency'] == 'AUD') selected @endif value="AUD">AUD</option>
                                <option @if($data[0]['currency'] == 'CAD') selected @endif value="CAD">CAD</option>
                                <option @if($data[0]['currency'] == 'GBP') selected @endif value="GBP">GBP</option>
                                <option @if($data[0]['currency'] == 'USD') selected @endif value="USD">USD</option>
                                <option @if($data[0]['currency'] == 'EUR') selected @endif value="EUR">EUR</option>
                                <option @if($data[0]['currency'] == 'SGD') selected @endif value="SGD">SGD</option>
                            </select> 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="amt">Terms :</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="terms">{{ $data[0]['terms'] }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="amt">RQ :</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="rqs" value="{{ $data[0]['rq'] }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="amt">Delivery Term :</label>
                        <div class="col-sm-9">
                            <select name="d_term" id="delivery_term" class="form-control">
                                <option @if($data[0]['delivery_term'] == 'blanket order') selected @endif value="blanket order">Blanket Order</option>
                                <option @if($data[0]['delivery_term'] == 'consignment items') selected @endif value="consignment items">Consignment Items</option>
                                <option @if($data[0]['delivery_term'] == 'one time shipment') selected @endif value="one time shipment">One Time Shipment</option>
                            </select> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn red" data-dismiss="modal">
                        <span class='glyphicon glyphicon-remove'></span> Close
                    </button>
                    <button type="submit" class="btn green updatePO">
                        <span class='glyphicon glyphicon-check'></span> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> -->


<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Edit Delivery Receiving Report
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" method="post" action="/ims/po/details/drr/edit">
                    @csrf
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="drr_id">DRR # :</label>
                        <div class="col-sm-9">
                            <input type="hidden" name="dr_id" id="dr_id">
                            <input type="text" class="form-control" id="drr_no" name="drr_no" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="qty">DRR Amount :</label>
                        <div class="col-sm-9">
                            <input type="text" required class="form-control" id="amt" name="amt">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="qty">DRR Qty :</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="qty" name="qty">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="inv">Invoice:</label>
                        <div class="col-sm-9">
                            <input type="name" class="form-control" id="inv" name="inv">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn red" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <span class='glyphicon glyphicon-check'></span> Update
                        </button>
                    </div>  
                </form>
            </div>
        </div>
    </div>
</div>

<!-- <div id="intransit" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Create Waybill
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form method="post" action="/inTransit" class="form-horizontal" role="form">
                    @csrf
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="dt">Delivery Type <i class="text-danger">*</i></label>
                        <div class="col-sm-9">
                            <input name="poid" type="hidden" class="form-control" value="{{$data[0]['id']}}">
                            <select required class="form-control" name="waybill_type">
                                <option value="">Choose Waybill Type</option>
                                <option value="partial">Partial</option>
                                <option value="full">Full</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3" for="dt">Waybill <i class="text-danger">*</i></label>
                        <div class="col-sm-9">
                            <input required type="text" class="form-control" name="waybill">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3" for="dt">Shipped Date <i class="text-danger">*</i></label>
                        <div class="col-sm-9">
                            <input required type="date" class="form-control" name="ship_dt">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <span class='glyphicon glyphicon-check'></span> Create
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> -->

<!-- <div id="custom" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Update Custom Clearing
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <form method="post" action="/updateCustom" class="form-horizontal" role="form">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="dt">Start Date :</label>
                        <div class="col-sm-9">
                            <input id="tid" name="wid" type="hidden" class="form-control">
                            <input type="date" class="form-control" id="s_dt" name="start">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="dt">Cleared Date :</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" id="c_dt" name="cleared">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn red btn-sm" data-dismiss="modal">
                        <span class='glyphicon glyphicon-remove'></span> Close
                    </button>
                    <button type="submit" class="btn blue btn-sm">
                        <span class='glyphicon glyphicon-check'></span> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> -->

<!-- <div id="delivered" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                For Delivery
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <form method="post" action="/deliveryDate" class="form-horizontal" role="form">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="dt">Delivered Date :</label>
                        <div class="col-sm-9">
                            <input id="did" name="wid" type="hidden" class="form-control">
                            <input id="wtype" name="wtype" type="hidden" class="form-control">
                            <input id="lpoid" name="poid" type="hidden" class="form-control">
                            <input type="date" class="form-control" name="dd">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn red btn-sm" data-dismiss="modal">
                        <span class='glyphicon glyphicon-remove'></span> Close
                    </button>
                    <button type="submit" class="btn blue btn-sm">
                        <span class='glyphicon glyphicon-check'></span> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> -->

<div id="addRemarks" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Remarks for Waybill
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="chat-form">
                        <div class="input-cont">
                            <input type="hidden" name="rid" id="rid">
                            <input type="hidden" name="pid" id="pid">
                            <input class="form-control" type="text"  name="i_remarks" id="i_remarks" placeholder="Input Remarks . . ." /> </div>
                        <div class="btn-cont">
                            <span class="arrow"> </span>
                            <button class="btn blue saveRemarks" type="submit"><i class="fa fa-plus icon-white"></i></button>
                        </div>
                    </div>
                    <br>
                        <div>
                            <table class="table table-hover table-striped text-center">
                                <tbody id="remarkslist">
                                </tbody>
                            </table>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- <div id="addLog" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Add Logistics
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="dt">Expected Delivery Date :</label>
                        <div class="col-sm-9">
                            <input type="hidden" value="{{ $data[0]['id'] }}" name="poid">
                            <input type="date" class="form-control" name="edd">
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm green addLogistics" data-dismiss="modal">
                        <span class='fa fa-save'></span> Submit
                    </button>
                    <button type="button" class="btn btn-sm red" data-dismiss="modal">
                        <span class='glyphicon glyphicon-remove'></span> Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div> -->

<!-- <div id="adrr" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Add Receiving Delivery Report
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" method="post" action="/ims/po/details/mcd/add" enctype="multipart/form-data">
                    @csrf

                    <input type="text" id="remaining_delivery" value="{{\App\PO::DeliveryBalanceStatic($data[0]['id']) }}">
                    <input type="text" id="remaining_delivered_value" value="{{ ($data[0]['amount'] - $ttamount) }}">

                    <div class="form-group">
                        <label class="control-label col-sm-3" for="pon">Waybill :</label>
                        <div class="col-sm-9">
                            <input type="hidden" name="pon" value="{{ $data[0]['poNumber'] }}">
                            <input type="hidden" name="pooid" value="{{ $data[0]['id'] }}">
                            <select required class="form-control" name="waybill" id="waybill">
                                <option value="">- - Select Waybill - -</option>
                                <option value="services"> Services</option>
                                @foreach($waybills as $w)
                                    <option value="{{$w->waybill}}">{{$w->waybill}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="pon">DRR # :</label>
                        <div class="col-sm-9">
                            <input type="text" required class="form-control" name="drn">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="amt">DRR Amount :</label>
                        <div class="col-sm-9">
                            <input type="text" required class="form-control" name="amt" id="drr_amt">
                            <span class="help-block" style="display:none;font-size:12px;color:red;" id="amt_balance_validation">Amount is greater than the remaining delivery value( {{ ($data[0]['amount'] - $ttamount) }} )</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="amt">DRR Qty :</label>
                        <div class="col-sm-9">
                            <input type="text" required class="form-control" name="qty" id="drr_qty">
                            <span class="help-block" style="display:none;font-size:12px;color:red;" id="qty_balance_validation">Quantity is greater than the remaining quantity( {{\App\PO::DeliveryBalanceStatic($data[0]['id']) }} )</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="amt">Invoice # :</label>
                        <div class="col-sm-9">
                            <input type="text" required class="form-control" name="inv">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="amt">Discrepancy :</label>
                        <div class="col-sm-9">
                            <input type="file" id="uploadFile" class="form-control" name="uploadFile[]" multiple/>

                            <div id="file_preview"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn red btn-sm" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Close
                        </button>
                        <button type="submit" class="btn green btn-sm">
                            <span class='glyphicon glyphicon-check'></span> Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> -->


<div id="upload_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Upload Shipment File
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" method="post" action="/shipment/file-upload" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="ship_id" name="ship_id">
                    <input type="hidden" id="poN" name="pon">
                    <div class="form-group">
                        <div class="col-sm-9">
                            <input type="file" id="uploadFile" class="form-control" name="uploadFile[]" multiple/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn red btn-sm" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <span class='glyphicon glyphicon-check'></span> Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
