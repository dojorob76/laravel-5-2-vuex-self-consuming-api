<!-- Activate Api Consumer Form -->
<form method="POST"
      action="{{action('ApiConsumerController@postActivate')}}"
      id="activate-api-consumer-form"
>
    @include('api_consumers.shared.forms._activate-api-consumer-form-fields')
</form>