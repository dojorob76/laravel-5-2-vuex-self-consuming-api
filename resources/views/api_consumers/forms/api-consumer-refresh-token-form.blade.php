<!-- Api Consumer Refresh Token Form -->
@include('global.forms._form-errors')
<form method="POST"
      action="{{action('ApiConsumerController@refreshToken')}}"
      id="api-consumer-refresh-token-form"
      class="form-horizontal"
>
    @include('api_consumers.shared.forms._api-consumer-refresh-token-form-fields')
    <!-- Submit Button -->
    <div class="form-group">
        <div class="col-sm-3"></div>
        <div class="col-sm-9">
            @include('global.forms._ajax-submit-button', ['data_prefix' => 'api-consumer-refresh-token-', 'data_wait' => 'Generating Token...', 'submit_text' => 'Refresh API Token'])
        </div>
    </div>
</form>