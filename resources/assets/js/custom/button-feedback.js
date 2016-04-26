var buttonFeedback = {
    formSubmit: function (form, display) {
        var submit = form.find(':submit');
        var origText = submit.find('.orig-text');
        var submitWait = submit.find('.btn-wait-content');
        var submitWaitText = origText.data('wait') ? origText.data('wait') : 'Working...';

        if (display == 'show') {
            this.showWaitFeedback(submit, origText, submitWait, submitWaitText);
        }

        if (display == 'hide') {
            this.hideWaitFeedback(submit, origText, submitWait);
        }
    },

    buttonClick: function (btn, display) {
        var origText = btn.find('.orig-text');
        var buttonWait = btn.find('.btn-wait-content');
        var buttonWaitText = origText.data('wait') ? origText.data('wait') : 'Loading. Please Wait...';

        if (display == 'show') {
            this.showWaitFeedback(btn, origText, buttonWait, buttonWaitText);
            if (btn.is(':submit')) {
                // This is a form, so let's make sure it gets submitted
                var form = btn.closest('form');
                form.submit();
            }
        }

        if (display == 'hide') {
            this.hideWaitFeedback(btn, origText, buttonWait);
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
