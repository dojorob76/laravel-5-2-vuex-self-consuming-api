{{csrf_field()}}
<!-- Email Field -->
<div class="form-group" id="create-api-consumer-email">
    <label for="email" class="col-sm-2 control-label">Email:</label>
    <div class="col-sm-10">
        <input type="email"
               name="email"
               value="{{session('email_address')}}"
               placeholder="Enter a Valid Email Address"
               class="form-control"
        >
        @include('global.forms._ajax-errors', ['e_pre' => 'create-api-consumer-email'])
    </div>
</div>