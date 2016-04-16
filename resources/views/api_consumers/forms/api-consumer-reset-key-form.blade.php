<!-- API Consumer Reset Key Form -->
@include('global.partials._form-errors')
<form method="POST"
      action="{{action('ApiConsumerController@postResetKey')}}"
      id="api-consumer-reset-key-form"
>
    @include('api_consumers.shared.forms._api-consumer-reset-key-form-fields')
    <p class="text-center">Click the button below to receive a Reset Key via email.</p>
    <button type="submit"
            class="btn btn-primary center-block ajax-validate"
            data-prefix="api-consumer-reset-key-"
    >
        @include('global.partials._button-wait')
        <span class="submit-text">Email Reset Key</span>
    </button>
</form>