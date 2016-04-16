<!-- Create API Consumer Form -->
<form method="POST"
      action="{{action('ApiConsumerController@store')}}"
      id="create-api-consumer-form"
>
    {{csrf_field()}}
    <div class="form-inline text-center">
        <div class="form-group" id="create-api-consumer-email">
            <label for="email">Email</label>
            <input type="email"
                   name="email"
                   value="{{session('email_address')}}"
                   placeholder="Enter a Valid Email Address"
                   class="form-control"
                   style="min-width: 300px;"
            >
            <button type="submit"
                    class="btn btn-primary ajax-validate"
                    data-prefix="create-api-consumer-"
            >
                @include('global.partials._button-wait')
                <span class="submit-text" data-wait="Generating Token...">
                    Get API Access Token
                </span>
            </button>
            <div class="errlist create-api-consumer-email-error-msg inline">
                <ul class="mb0"></ul>
            </div>
        </div>
    </div>
</form>