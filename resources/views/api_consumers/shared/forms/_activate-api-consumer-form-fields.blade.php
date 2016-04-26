<!-- Shared Fields for the Activate API Consumer Form -->
{{ csrf_field() }}
<input type="hidden" name="starter_token" value="{{session('access_token')}}">
<input type="hidden" name="api_consumer_id" value="{{session('access_id')}}">
<feedback-button wait-txt="Activating Token..." btn-class="danger" ext-class=" add-feedback center-block">
    Activate Token
</feedback-button>