<!-- Shared Fields for the API Consumer Resend Reset Key Form -->
{{ csrf_field() }}
<!-- (HIDDEN) Consumer ID Field -->
<div class="form-group" id="api-consumer-resend-reset-key-consumer_id">
    <input type="hidden" name="consumer_id" value="{{$api_consumer->id}}">
    @include('global.forms._ajax-errors', ['e_pre' => 'api-consumer-resend-reset-key-consumer_id', 'e_class' => 'rounded'])
</div>