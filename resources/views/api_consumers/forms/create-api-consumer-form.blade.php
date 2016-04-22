<!-- Create API Consumer Form -->
<form method="POST"
      action="{{action('ApiConsumerController@store')}}"
      id="create-api-consumer-form"
      class="form-horizontal"
>
    @include('api_consumers.shared.forms._create-api-consumer-form-fields')
    <div class="form-group">
        <div class="col-sm-10 col-sm-offset-2">
            @include('global.forms._ajax-submit-button', ['data_prefix' => 'create-api-consumer-', 'data_wait' => 'Generating Token...', 'submit_text' => 'Get API Access Token'])
        </div>
    </div>
</form>