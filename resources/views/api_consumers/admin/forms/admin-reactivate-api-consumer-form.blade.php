<!-- Admin Reactivate Api Consumer Form -->
@include('global.forms._form-errors')
<form method="POST"
      action="{{action('Admin\AdminApiConsumerController@postReactivate')}}"
      id="admin-reactivate-api-consumer-form"
      class="form-horizontal"
>
    @include('api_consumers.shared.forms._reactivate-api-consumer-form-fields')
    <!-- Submit Button -->
    <div class="form-group">
        <div class="col-sm-10 col-sm-offset-2">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
</form>