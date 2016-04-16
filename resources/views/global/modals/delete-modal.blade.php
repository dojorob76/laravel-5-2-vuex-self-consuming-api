<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close modal-close-btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="deleteModalLabel">Confirm Delete</h4>
            </div>

            <div class="modal-body">
                <p class="text-center">
                    <span class="glyphicon glyphicon-warning-sign" style="color: #f0ad4e;"></span>&nbsp;
                    <em>Are you sure</em> you want to delete this <span class="custom-text"></span>?
                </p>
                <p class="text-center">
                    <strong>This action can not be undone.</strong>
                </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default modal-close-btn modal-bottom-close" data-dismiss="modal">
                    No, Cancel
                </button>
                <button type="button" class="btn btn-danger modal-delete">
                    @include('global.partials._button-wait')
                    <span class="button-text" data-wait="Deleting...">Yes, Delete</span>
                </button>
            </div>

        </div>
    </div>
</div>