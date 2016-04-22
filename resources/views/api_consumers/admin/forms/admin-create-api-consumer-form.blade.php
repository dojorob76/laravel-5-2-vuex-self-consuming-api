<!-- Admin Create API Consumer Form -->
@include('global.forms._form-errors')
<form method="POST"
      action="{{action('Admin\AdminApiConsumerController@store')}}"
      id="admin-create-api-consumer-form"
      class="form-horizontal"
>
    @include('api_consumers.shared.forms._create-api-consumer-form-fields')
    <!-- Submit Button -->
    <div class="form-group">
        <div class="col-sm-10 col-sm-offset-2">
            <button type="submit" class="btn btn-primary">Generate Token</button>
        </div>
    </div>
</form>