<!-- Admin Reactivate Api Consumer Form -->
@include('global.partials._form-errors')
<form method="POST"
      action="{{action('Admin\AdminApiConsumerController@postReactivate')}}"
      id="admin-reactivate-api-consumer-form"
>
    @include('api_consumers.shared.forms._reactivate-api-consumer-form-fields')
</form>