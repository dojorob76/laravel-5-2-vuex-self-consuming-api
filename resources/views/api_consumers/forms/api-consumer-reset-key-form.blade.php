<!-- API Consumer Reset Key Form -->
@include('global.partials._form-errors')
<form method="POST"
      action="{{action('ApiConsumerController@postResetKey')}}"
      id="api-consumer-reset-key-form"
>
    @include('api_consumers.shared.forms._api-consumer-reset-key-form-fields')
    <p class="text-center">Click the button below to receive a Reset Key via email.</p>
    <button type="submit" class="btn btn-primary center-block">
        Email Reset Key
    </button>
</form>