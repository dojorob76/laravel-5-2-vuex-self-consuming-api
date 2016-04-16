var formErrors = {
    set: function (errors, prefix) {
        $.each(errors, function (index, value) {
            var msgtxt = '';
            $.each(value, function (key, msg) {
                msgtxt += '<li>' + msg + '</li>';
            });
            $('#' + prefix + index).addClass('has-error');
            $('.' + prefix + index + '-error-msg ul').html(msgtxt);
        });
    },
    clear: function () {
        // Hide the errors displayed beneath the form fields
        $('.form-group').each(function () {
            if ($(this).hasClass('has-error')) {
                $(this).removeClass('has-error');
            }
        });
        // Hide any errors that may have been displayed in an alert box
        $('.error-msgs').hide();
    }
};