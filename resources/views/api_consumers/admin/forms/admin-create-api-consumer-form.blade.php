<!-- Admin Create API Consumer Form -->
@include('global.partials._form-errors')
<form method="POST"
      action="{{action('Admin\AdminApiConsumerController@store')}}"
      id="admin-create-api-consumer-form"
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
            <button type="submit" class="btn btn-primary">Generate Token</button>
            <div class="errlist create-api-consumer-email-error-msg rounded">
                <ul class="mb0"></ul>
            </div>
        </div>
    </div>
</form>