<script>
    $('.ajax-auth').on('click', function (e) {
        e.preventDefault();

        var form = $(this).closest('form');
        var prefix = $(this).data('prefix');
        var data = form.serialize();

        var successCallback = [successCb.setAuthorized];

        ajaxForm.validate(form, prefix, data, successCallback, null);
    });
</script>