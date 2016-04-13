<!-- Admin Update API Consumer Form -->
@include('global.partials._form-errors')
<form method="POST"
      action="{{action('Admin\AdminApiConsumerController@update', $api_consumer->id)}}"
      id="admin-update-api-consumer-form"
>
    @include('api_consumers.shared.forms._update-api-consumer-form-fields')
    <!-- Level Field -->
    <div class="form-group" id="update-api-consumer-level">
        <label for="level">API Access Level</label>
        <input type="text" name="level" value="{{$api_consumer->level}}" class="form-control">
        <div class="errlist udpate-api-consumer-level-error-msg"><ul class="mb0"></ul></div>
    </div>
    <button type="submit" class="btn btn-primary center-block">Update API Consumer</button>
</form>