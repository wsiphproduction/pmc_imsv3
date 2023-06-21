<div id="paidModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Mark as Paid
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <!-- <form class="form-horizontal" role="form" method="post" action="/mark_as_paid" enctype="multipart/form-data"> -->

                <form id="form" class="form-horizontal" action="{{ route('modal.payments.mark_as_paid') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input id="poid" name="poid" type="hidden">
                    <input id="pon" name="pon" type="hidden">
                    <input id="ppid" name="ppid" type="hidden">

                    <div class="form-group">
                        <label class="control-label col-sm-3" for="amt">Amount :</label>
                        <div class="col-sm-9">
                            <input name="amount" type="text" readonly class="form-control" id="amount">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="dt">Remarks :</label>
                        <div class="col-sm-9">
                            <textarea required placeholder="Remarks" class="form-control" name="remarks"></textarea>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label class="control-label col-md-3">Attachment <i class="data-danger">*</i></label>
                        <div class="col-md-9">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 250px; height: 200px;"> </div>
                                <div>
                                    <span class="btn red btn-outline btn-file">
                                        <span class="fileinput-new"> Select File </span>
                                        <span class="fileinput-exists"> Change </span>
                                        <input required type="file" name="uploadFile[]"> </span>
                                    <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <span class='glyphicon glyphicon-check'></span> Mark as Paid
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delete_payment" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('payment.delete') }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Delete Confirmation</h4>
                </div>
                <div class="modal-body"> You're about to delete this payment. Do you want to continue ?
                    <input type="hidden" name="pid" id="d_pid">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn red">Yes, Delete</button>
                </div>
            </div>
        </form>  
    </div>
</div>

<div class="modal effect-scale" id="prompt-no-value" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <form action="/delete-payment" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Error</h4>
                </div>
                <div class="modal-body"> Please provide a valid date range.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>  
    </div>
</div>