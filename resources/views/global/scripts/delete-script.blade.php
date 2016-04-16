<script>
    $('#deleteModal').on('show.bs.modal', function(event){
        // Get the button that triggered the modal
        var button = $(event.relatedTarget);
        // Get the modal instance
        var modal = $(this);
        // Get the form that the delete button is connected to
        var form = button.closest('form');
        // Get the optional custom text for the "Are you sure..." question or default to "item"
        var customTxt = form.data('modal-text') ? form.data('modal-text') : 'item';
        // Add the custom or default text to the modal body
        modal.find('.custom-text').text(customTxt);

        // If the User confirms the delete, submit the form
        $('.modal-delete').on('click', function(e){
            e.preventDefault();
            // Prevent the modal from closing during this process
            bsModal.preventClose(modal);
            // Submit the form
            ajaxForm.modalDelete(form, modal, customTxt);
        });
    });
</script>