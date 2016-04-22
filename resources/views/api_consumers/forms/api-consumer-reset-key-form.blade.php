<!-- API Consumer Reset Key Form -->
@include('global.forms._form-errors')
<form method="POST"
      action="{{action('ApiConsumerController@postResetKey')}}"
      id="api-consumer-reset-key-form"
>
    @include('api_consumers.shared.forms._api-consumer-reset-key-form-fields')
    <p class="text-center">Click the button below to receive a Reset Key via email.</p>
    @include('global.forms._ajax-submit-button', ['data_prefix' => 'api-consumer-reset-key-', 'submit_text' => 'Email Reset Key', 'btn_class' => 'center-block'])
</form>