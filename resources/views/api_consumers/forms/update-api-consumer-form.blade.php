<!-- Update API Consumer Form -->
<form method="POST"
      action="{{action('ApiConsumerController@update', $api_consumer->id)}}"
      id="update-api-consumer-form"
      class="form-horizontal"
>
    @include('api_consumers.shared.forms._update-api-consumer-form-fields')
    <!-- Submit Button -->
    <div class="form-group">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            @include('global.forms._ajax-submit-button', ['data_prefix' => 'update-api-consumer-', 'submit_text' => 'Udpate Email'])
        </div>
    </div>
</form>