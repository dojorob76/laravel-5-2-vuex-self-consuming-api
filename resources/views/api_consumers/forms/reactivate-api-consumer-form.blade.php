<!-- Reactivate Api Consumer Form -->
<form method="POST"
      action="{{action('ApiConsumerController@postReactivate')}}"
      id="reactivate-api-consumer-form"
>
    @include('api_consumers.shared.forms._reactivate-api-consumer-form-fields')
</form>