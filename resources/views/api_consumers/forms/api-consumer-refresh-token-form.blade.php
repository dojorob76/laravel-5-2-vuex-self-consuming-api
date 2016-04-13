<!-- Api Consumer Refresh Token Form -->
<form method="POST"
      action="{{action('ApiConsumerController@refreshToken')}}"
      id="api-consumer-refresh-token-form"
>
    @include('api_consumers.shared.forms._api-consumer-refresh-token-form-fields')
</form>