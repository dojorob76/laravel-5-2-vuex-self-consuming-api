<!-- Admin Activate Api Consumer Form -->
@include('global.forms._form-errors')
<form method="POST"
      action="{{action('Admin\AdminApiConsumerController@postActivate')}}"
      id="admin-activate-api-consumer-form"
>
    @include('api_consumers.shared.forms._activate-api-consumer-form-fields')
</form>