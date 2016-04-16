<!-- Shared Fields for the API Consumer Reset Key Form -->
{{ csrf_field() }}
<!-- (HIDDEN) Consumer ID Field -->
<div class="form-group" id="api-consumer-reset-key-consumer_id">
    <input type="hidden" name="consumer_id" value="{{$api_consumer->id}}">
    <div class="errlist api-consumer-reset-key-consumer_id-error-msg rounded">
        <ul class="mb0"></ul>
    </div>
</div>