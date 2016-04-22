<!-- Add Feedback to buttons that are certain to result in a page redirect or reload -->
<script>
    $('.add-feedback').on('click', function () {
        buttonFeedback.buttonClick($(this), 'show');
    });
</script>

<!-- Execute basic AJAX form validation with no callbacks -->
<script>
    $('.ajax-validate').on('click', function (e) {
        e.preventDefault();

        var form = $(this).closest('form');
        var prefix = $(this).data('prefix');
        var data = form.serialize();

        ajaxForm.validate(form, prefix, data, null, null);
    });
</script>

<!-- Initialize Bootstrap Tooltips -->
<script>
    $('[data-toggle="tooltip"]').tooltip();
</script>