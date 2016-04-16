<!-- Api Consumer Refresh Token Form -->
@include('global.partials._form-errors')
<form method="POST"
      action="{{action('ApiConsumerController@refreshToken')}}"
      id="api-consumer-refresh-token-form"
>
    @include('api_consumers.shared.forms._api-consumer-refresh-token-form-fields')
    <button type="submit"
            class="btn btn-primary center-block ajax-validate submit-form"
            data-prefix="api-consumer-refresh-token-"
    >
        @include('global.partials._button-wait')
        <span class="submit-text" data-wait="Generating Token...">
            Refresh API Token
        </span>
    </button>
</form>