<!-- Admin Create API Consumer Form -->
@include('global.partials._form-errors')
<form method="POST"
      action="{{action('Admin\AdminApiConsumerController@store')}}"
      id="admin-create-api-consumer-form"
>
    @include('api_consumers.shared.forms._create-api-consumer-form-fields')
</form>