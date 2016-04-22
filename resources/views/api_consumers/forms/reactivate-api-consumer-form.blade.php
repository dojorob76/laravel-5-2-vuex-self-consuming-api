<!-- Reactivate Api Consumer Form -->
<form method="POST"
      action="{{action('ApiConsumerController@postReactivate')}}"
      id="reactivate-api-consumer-form"
      class="form-horizontal"
>
    @include('api_consumers.shared.forms._reactivate-api-consumer-form-fields')
    <!-- Submit Button -->
    <div class="form-group">
        <div class="col-sm-10 col-sm-offset-2">
            @include('global.forms._ajax-submit-button', ['data_prefix' => 'reactivate-api-consumer-'])
        </div>
    </div>
</form>