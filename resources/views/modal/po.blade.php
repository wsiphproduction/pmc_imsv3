

<div class="modal fade" id="close_po" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <form action="/manual_close_po" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Close Confirmation</h4>
                </div>
                <div class="modal-body"> You're about to close this PO. Do you want to continue ?
                    <input type="hidden" name="cid" id="cid">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn red">Yes, Close this PO</button>
                </div>
            </div>
        </form>  
    </div>
</div>

<div class="modal fade" id="delete_po" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <form action="/manual_delete_po" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Delete Confirmation</h4>
                </div>
                <div class="modal-body"> You're about to delete this PO. Do you want to continue ?
                    <input type="hidden" name="pid" id="did">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn red">Yes, Delete this PO</button>
                </div>
            </div>
        </form>  
    </div>
</div>