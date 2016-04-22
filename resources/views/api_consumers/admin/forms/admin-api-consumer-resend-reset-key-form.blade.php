<!-- ADMIN API Consumer Resend Reset Key Form -->
<form method="POST"
      action="{{action('Admin\AdminApiConsumerController@postResetKey')}}"
      id="admin-api-consumer-resend-reset-key-form"
>
    @include('api_consumers.shared.forms._api-consumer-resend-reset-key-form-fields')
    <button type="submit"
            class="btn btn-default center-block"
            data-prefix="api-consumer-resend-reset-key-"
    >
        Refresh Reset Key
    </button>
</form>