var ajaxForm = {

    /**
     * @param form - object | The form to be processed
     * @param prefix - string | The error message prefix (set as 'data-prefix' data attribute on submit button)
     * @param requestData - URL encoded text string | serialized form data (i.e., 'form.serialize()')
     * @param successCallbacks - array, null | function(s) to be executed on success (EXAMPLE:[successCb.setAuthorized])
     * @param errorCallbacks - array, null | function(s) to be executed on error (See example above)
     */
    validate: function (form, prefix, requestData, successCallbacks, errorCallbacks) {

        // Clear any existing form errors on the page
        formErrors.clear();

        // Disable the Submit Button and add feedback text
        buttonFeedback.formSubmit(form, 'show');

        // Be prepared for form method spoofing...
        var formId = form.attr('id');
        var method = $("#" + formId + ' ' + "input[name=_method]").length ? $("#" + formId + ' ' + "input[name=_method]").val() : form.attr('method');

        var ajaxRequest = $.ajax({
            url: form.attr('action'),
            type: method,
            data: requestData
        });

        ajaxRequest.done(function (data, textStatus, jqXHR) {
            // If success callbacks were provided, execute each one of them
            if (successCallbacks != null) {
                this.executeCallbacks(successCallbacks, data, textStatus, jqXHR);
            }
        });

        ajaxRequest.fail(function (data, textStatus, jqXHR) {
            try{
                var errors = $.parseJSON(data.responseText);
                formErrors.set(errors, prefix);
            }
            catch (e){
                // Not a Form Request fail
            }

            // If error callbacks were provided, execute each one of them
            if (errorCallbacks != null) {
                this.executeCallbacks(errorCallbacks, data, textStatus, jqXHR);
            }
        });

        ajaxRequest.always(function (data, textStatus, jqXHR) {
            // Make sure we can work with the returned data
            pData = ajaxForm.parsedData(data);

            // If a redirect path has been provided, redirect there now
            if (pData.redirector) {
                location.replace(pData.redirector);
            }
            // If the reloader has been called, refresh the page
            else if (pData.reloader) {
                location.reload();
            }
            else{
                // No refresh is happening, so reinstate the Submit Button
                buttonFeedback.formSubmit(form, 'hide');
            }
        });
    },

    /**
     * Send an AJAX request to delete a model when the user has confirmed the delete from the delete modal.
     *
     * @param form | Object - The form to be processed
     * @param modal | Object - The delete Modal instance
     * @param customTxt | String - Custom descriptor of the item that is being deleted
     */
    modalDelete: function(form, modal, customTxt){
        // Disable the Delete Button and add feedback text
        buttonFeedback.buttonClick(modal.find('.modal-delete'), 'show');

        var responseMsg = '';

        var ajaxRequest = $.ajax({
            url: form.attr('action'),
            type: 'DELETE'
        });

        ajaxRequest.done(function(data, textStatus, jqXHR){
            var icon = '<span class="glyphicon glyphicon-check" style="color: #5cb85c;"></span>&nbsp;';
            responseMsg += '<p class="text-center">' + icon + 'The ' + customTxt + 'has been deleted.</p>';
            modal.find('.modal-header h4').text('Success!');
        });

        ajaxRequest.fail(function(data, textStatus, jqXHR){
            var icon = '<span class="glyphicon glyphicon-exclamation-sign" style="color: #d9534f;"></span>&nbsp;';
            responseMsg += '<p class="text-center">' + icon + 'The' + customTxt + ' could not be deleted</p>';
            modal.find('.modal-header h4').text('Error!');
        });

        ajaxRequest.always(function(data, textStatus, jqXHR){
            // Allow the modal to be closed again
            bsModal.allowClose(modal);
            // Change the Close button text
            modal.find('.modal-bottom-close').text('Close');
            // Hide the Delete Button
            modal.find('.modal-delete').hide();
            // Make sure we can work with our data
            var pData = ajaxForm.parsedData(data);
            // If a redirector has been assigned, append feedback to the body content and redirect on modal close
            if(pData.redirector){
                responseMsg += '<p class="text-center">You will be redirected when you click the Close button.</p>';
                modal.on('hide.bs.modal', function(e){
                    window.location.replace(pData.redirector);
                });
            }
            // If a reloader has been assigned, append feedback to the body content and reload the page on modal close
            if(pData.reloader){
                responseMsg += '<p class="text-center">This page will be refreshed when you click the Close button.</p>';
                modal.on('hide.bs.modal', function(e){
                    window.location.reload();
                });
            }
            // Update the modal body with the appropriate feedback
            modal.find('.modal-body').html(responseMsg);
        });
    },

    executeCallbacks: function(callbacks, data, textStatus, jqXHR){
        $.each(callbacks, function (index, value) {
            value(data, textStatus, jqXHR);
        });
    },

    parsedData: function(data){
        try{
            return $.parseJSON(data.responseText);
        }
        catch(e){
            return data;
        }
    }
};