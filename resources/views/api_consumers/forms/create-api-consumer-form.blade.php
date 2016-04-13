<!-- Create API Consumer Form -->
<form method="POST"
      action="{{action('ApiConsumerController@store')}}"
      id="create-api-consumer-form"
>
    @include('api_consumers.shared.forms._create-api-consumer-form-fields')
</form>