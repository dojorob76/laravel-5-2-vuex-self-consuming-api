<!-- Shared Fields for the API Consumer Refresh Token Form -->
{{ csrf_field() }}
<!-- (HIDDEN) Email Field -->
<div class="form-group" id="api-consumer-refresh-token-email">
    <input type="hidden" name="email" value="{{$api_consumer->email}}">
    <div class="errlist api-consumer-refresh-token-email-error-msg"><ul class="mb0"></ul></div>
</div>
<!-- Reset Key Field -->
<div class="form-group" id="api-consumer-refresh-token-reset_key">
    <label for="reset_key">Reset Key</label>
    <input type="text" name="reset_key" placeholder="'Enter your API Token Reset Key" class="form-control">
    <div class="errlist api-consumer-refresh-token-reset_key-error-msg"><ul class="mb0"></ul></div>
</div>