<!-- Admin API Consumer Reset Key Form -->
<form method="POST"
      action="{{action('Admin\AdminApiConsumerController@postResetKey')}}"
      id="admin-api-consumer-reset-key-form"
>
    @include('api_consumers.shared.forms._api-consumer-reset-key-form-fields')
    <p class="text-center">Click the button below to generate a Reset Key.</p>
    <button type="submit" class="btn btn-primary center-block">Generate Reset Key</button>
</form>