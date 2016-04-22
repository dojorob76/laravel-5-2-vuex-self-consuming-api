<!-- Admin Update API Consumer Form -->
<form method="POST"
      action="{{action('Admin\AdminApiConsumerController@update', $api_consumer->id)}}"
      id="admin-update-api-consumer-form"
      class="form-horizontal"
>
    @include('api_consumers.shared.forms._update-api-consumer-form-fields')
    <!-- Level Field -->
    <div class="form-group" id="update-api-consumer-level">
        <label for="level" class="col-sm-2 control-label">Level:</label>
        <div class="col-sm-10">
            <input type="text" name="level" value="{{$api_consumer->level}}" class="form-control">
            @include('global.forms._ajax-errors', ['e_pre' => 'update-api-consumer-level'])
        </div>
    </div>
    <!-- Submit Button -->
    <div class="form-group">
        <div class="col-sm-10 col-sm-offset-2">
            <button type="submit" class="btn btn-primary">Update API Consumer</button>
        </div>
    </div>
</form>