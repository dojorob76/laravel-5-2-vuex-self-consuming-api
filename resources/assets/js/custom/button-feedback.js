var buttonFeedback = {
    formSubmit: function (form, display) {
        var submit = form.find(':submit');
        var submitText = submit.find('.submit-text');
        var submitWait = submit.find('.btn-wait-content');
        var submitWaitText = submitText.data('wait') ? submitText.data('wait') : 'Working...';

        if (display == 'show') {
            this.showWaitFeedback(submit, submitText, submitWait, submitWaitText);
        }

        if (display == 'hide') {
            this.hideWaitFeedback(submit, submitText, submitWait);
        }
    },

    buttonClick: function (btn, display) {
        var buttonText = btn.find('.button-text');
        var buttonWait = btn.find('.btn-wait-content');
        var buttonWaitText = buttonText.data('wait') ? buttonText.data('wait') : 'Loading. Please Wait...';

        if (display == 'show') {
            this.showWaitFeedback(btn, buttonText, buttonWait, buttonWaitText);
            if (btn.is(':submit')) {
                // This is a form, so let's make sure it gets submitted
                var form = btn.closest('form');
                form.submit();
            }
        }

        if (display == 'hide') {
            this.hideWaitFeedback(btn, buttonText, buttonWait);
        }
    },

    // Hide the original button text, and display the wait content
    showWaitFeedback: function (btn, btnEl, waitEl, waitTxt) {
        btn.prop("disabled", true);
        btnEl.hide();
        waitEl.addClass('show-me').find('.btn-wait-text').text(waitTxt);
    },

    // Hide the wait content, and display the original button text
    hideWaitFeedback: function (btn, btnEl, waitEl) {
        waitEl.removeClass('show-me');
        btnEl.show();
        btn.prop("disabled", false);
    }
};
