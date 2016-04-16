<!-- Shared Fields for the Activate API Consumer Form -->
{{ csrf_field() }}
<input type="hidden" name="starter_token" value="{{session('access_token')}}">
<input type="hidden" name="api_consumer_id" value="{{session('access_id')}}">
<button type="submit" class="btn btn-danger center-block add-feedback">
    @include('global.partials._button-wait')
    <span class="button-text" data-wait="Activating Token...">Activate Token</span>
</button>