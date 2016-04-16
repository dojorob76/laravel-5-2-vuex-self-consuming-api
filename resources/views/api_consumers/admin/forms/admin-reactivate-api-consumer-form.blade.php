<!-- Admin Reactivate Api Consumer Form -->
@include('global.partials._form-errors')
<form method="POST"
      action="{{action('Admin\AdminApiConsumerController@postReactivate')}}"
      id="admin-reactivate-api-consumer-form"
>
    {{csrf_field()}}
    <div class="form-inline text-center">
        <!-- Email Field -->
        <div class="form-group" id="reactivate-api-consumer-email">
            <label for="email">Email Address</label>
            <input type="email"
                   name="email"
                   value="{{old('email')}}"
                   placeholder="Re-Enter Your Email Address"
                   class="form-control"
                   style="min-width: 300px;"
            >
            <button type="submit" class="btn btn-primary">Try Again</button>
            <div class="errlist reactivate-api-consumer-email-error-msg rounded">
                <ul class="mb0"></ul>
            </div>
        </div>
    </div>
</form>