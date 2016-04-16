<!-- API Consumer Web Access Form -->
<form method="POST"
      action="{{action('ApiConsumerController@accessWebApp')}}"
      id="api-consumer-web-access-form"
>
    <div id="validation-prefix" data-prefix="api-consumer-web-access-"></div>
    {{ csrf_field() }}

    <!-- Email Field -->
    <div class="form-group" id="api-consumer-web-access-email">
        <label for="email">Email</label>
        <input type="email"
               name="email"
               placeholder="Please enter your API Account email address"
               class="form-control"
        >
        <div class="errlist api-consumer-web-access-email-error-msg"><ul class="mb0"></ul></div>
    </div>

    <!-- API Token Field -->
    <div class="form-group" id="api-consumer-web-access-api_token">
        <label for="api_token">API Token</label>
        <input type="text"
               name="api_token"
               placeholder="Please enter your API Access Token"
               class="form-control"
        >
        <div class="errlist api-consumer-web-access-api_token-error-msg"><ul class="mb0"></ul></div>
    </div>

    <button type="submit"
            class="btn btn-primary center-block ajax-validate"
            data-prefix="api-consumer-web-access-"
    >
        @include('global.partials._button-wait')
        <span class="submit-text" data-wait="Authenticating...">
            Access My Account
        </span>
    </button>
</form>