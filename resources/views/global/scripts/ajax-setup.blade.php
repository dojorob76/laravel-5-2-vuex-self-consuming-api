<script>
    $.ajaxSetup({
        headers: {
            'Authorization': jwToken.setHeaderFromCookie(),
            'X-CSRF-TOKEN': appGlobals.csrf
        }
    });
</script>