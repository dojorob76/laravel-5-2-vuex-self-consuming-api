<!-- Admin Api Consumer Refresh Token Form -->
@include('global.partials._form-errors')
<form method="POST"
      action="{{action('Admin\AdminApiConsumerController@refreshToken')}}"
      id="admin-api-consumer-refresh-token-form"
>
    @include('api_consumers.shared.forms._api-consumer-refresh-token-form-fields')
    <button type="submit" class="btn btn-primary center-block">Refresh API Token</button>
</form>