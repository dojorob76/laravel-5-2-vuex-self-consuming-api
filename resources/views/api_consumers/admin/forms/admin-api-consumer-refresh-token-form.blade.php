<!-- Admin Api Consumer Refresh Token Form -->
<form method="POST"
      action="{{action('Admin\AdminApiConsumerController@refreshToken')}}"
      id="admin-api-consumer-refresh-token-form"
      class="form-horizontal"
>
    @include('api_consumers.shared.forms._api-consumer-refresh-token-form-fields')
    <!-- Submit Button -->
    <div class="form-group">
        <div class="col-sm-9 col-sm-offset-3">
            <button type="submit" class="btn btn-primary">Refresh API Token</button>
        </div>
    </div>
</form>