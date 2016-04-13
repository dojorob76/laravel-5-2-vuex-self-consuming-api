<!-- Shared Fields for the API Consumer Refresh Token Form -->
{{ csrf_field() }}
<input type="hidden" name="email" value="{{$api_consumer->email}}">
<!-- Reset Key Field -->
<div class="form-group" id="api-consumer-refresh-token-reset_key">
    <label for="reset_key">Reset Key</label>
    <input type="text" name="reset_key" placeholder="'Enter your API Token Reset Key" class="form-control">
    <div class="errlist api-consumer-refresh-token-reset_key-error-msg">
        <ul style="margin-bottom: 0;"></ul>
    </div>
</div>
<button type="submit" class="btn btn-primary center-block">Refresh API Token</button>