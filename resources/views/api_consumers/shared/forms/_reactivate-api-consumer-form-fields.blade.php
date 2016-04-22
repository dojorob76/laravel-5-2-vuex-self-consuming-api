{{csrf_field()}}
<!-- Email Field -->
<div class="form-group" id="reactivate-api-consumer-email">
    <label for="email" class="col-sm-2 control-label">Email:</label>
    <div class="col-sm-10">
        <input type="email"
               name="email"
               value="{{old('email')}}"
               placeholder="Please Re-Enter Your Email Address"
               class="form-control"
        >
        @include('global.forms._ajax-errors', ['e_pre' => 'reactivate-api-consumer-email'])
    </div>
</div>