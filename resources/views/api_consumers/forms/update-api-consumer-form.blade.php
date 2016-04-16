<!-- Update API Consumer Form -->
<form method="POST"
      action="{{action('ApiConsumerController@update', $api_consumer->id)}}"
      id="update-api-consumer-form"
>
    @include('api_consumers.shared.forms._update-api-consumer-form-fields')
    <button type="submit"
            class="btn btn-primary center-block ajax-validate"
            data-prefix="update-api-consumer-"
    >
        @include('global.partials._button-wait')
        <span class="submit-text" data-wait="Updating...">
            Update Email
        </span>
    </button>
</form>