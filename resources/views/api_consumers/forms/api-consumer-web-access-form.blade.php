<!-- API Consumer Web Access Form -->
<form method="POST"
      action="{{action('ApiConsumerController@accessWebApp')}}"
      id="api-consumer-web-access-form"
      class="form-horizontal"
>
    {{ csrf_field() }}

    <!-- Email Field -->
    <div class="form-group" id="api-consumer-web-access-email">
        <label for="email" class="col-sm-3 control-label">Email:</label>
        <div class="col-sm-9">
            <input type="email"
                   name="email"
                   value="{{old('email')}}"
                   placeholder="Please enter your email"
                   class="form-control"
            >
            @include('global.forms._ajax-errors', ['e_pre' => 'api-consumer-web-access-email'])
        </div>
    </div>

    <!-- API Token Field -->
    <div class="form-group" id="api-consumer-web-access-api_token">
        <label for="api_token" class="col-sm-3 control-label">
            <span class="hidden-sm">API </span>Token:
        </label>
        <div class="col-sm-9">
            <input type="text"
                   name="api_token"
                   value="{{old('api_token')}}"
                   placeholder="Please enter your API Token"
                   class="form-control"
            >
            @include('global.forms._ajax-errors', ['e_pre' => 'api-consumer-web-access-api_token'])
        </div>
    </div>

    <!-- Form Submit -->
    <div class="form-group">
        <div class="col-sm-3"></div>
        <div class="col-sm-9">
            @include('global.forms._ajax-submit-button', ['data_prefix' => 'api-consumer-web-access-', 'data_wait' => 'Authenticating...', 'submit_text' => 'Access My Account'])
        </div>
    </div>
</form>