<!-- Shared Fields for the API Consumer Refresh Token Form -->
{{ csrf_field() }}
<!-- (HIDDEN) Email Field -->
<div class="form-group" id="api-consumer-refresh-token-email">
    <input type="hidden" name="email" value="{{$api_consumer->email}}">
    @include('global.forms._ajax-errors', ['e_pre' => 'api-consumer-refresh-token-email'])
</div>
<!-- Reset Key Field -->
<div class="form-group" id="api-consumer-refresh-token-reset_key">
    <label for="reset_key" class="control-label col-sm-3">Reset Key:</label>
    <div class="col-sm-9">
        <input type="text" name="reset_key" placeholder="Enter your Reset Key" class="form-control">
        @include('global.forms._ajax-errors', ['e_pre' => 'api-consumer-refresh-token-reset_key'])
    </div>
</div>