<!-- Update API Consumer Form -->
<form method="POST"
      action="{{action('ApiConsumerController@update', $api_consumer->id)}}"
      id="update-api-consumer-form"
>
    @include('api_consumers.shared.forms._update-api-consumer-form-fields')
    <button type="submit" class="btn btn-primary center-block">Update</button>
</form>