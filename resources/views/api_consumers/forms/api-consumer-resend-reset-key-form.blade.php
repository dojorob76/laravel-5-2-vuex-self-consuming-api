<!-- API Consumer Resend Reset Key Form -->
@include('global.forms._form-errors')
<form method="POST"
      action="{{action('ApiConsumerController@postResetKey')}}"
      id="api-consumer-resend-reset-key-form"
>
    @include('api_consumers.shared.forms._api-consumer-resend-reset-key-form-fields')
    <button type="submit"
            class="btn btn-default ajax-validate center-block"
            data-prefix="api-consumer-resend-reset-key-"
    >
        @include('global.partials._button-wait')
        <span class="orig-text" data-wait="Resending Email...">
            Resend Reset Key
        </span>
    </button>
</form>