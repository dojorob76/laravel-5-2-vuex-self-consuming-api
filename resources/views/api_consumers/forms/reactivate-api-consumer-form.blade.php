<!-- Reactivate Api Consumer Form -->
<form method="POST"
      action="{{action('ApiConsumerController@postReactivate')}}"
      id="reactivate-api-consumer-form"
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
            <button type="submit"
                    class="btn btn-primary ajax-validate"
                    data-prefix="reactivate-api-consumer-"
            >
                @include('global.partials._button-wait')
                <span class="submit-text">Try Again</span>
            </button>
            <div class="errlist reactivate-api-consumer-email-error-msg inline">
                <ul class="mb0"></ul>
            </div>
        </div>
    </div>
</form>