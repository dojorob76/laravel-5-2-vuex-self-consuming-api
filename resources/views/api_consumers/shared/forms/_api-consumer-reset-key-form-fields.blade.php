<!-- Shared Fields for the API Consumer Reset Key Form -->
{{ csrf_field() }}
<input type="hidden" name="consumer_id" value="{{$api_consumer->id}}">