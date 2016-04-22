<!-- Shared Fields for the Update API Consumer Form -->
{{ method_field('PUT') }}
{{ csrf_field() }}
<!-- (HIDDEN) ID Field -->
<div class="form-group" id="update-api-consumer-id">
    <input type="hidden" name="id" value="{{$api_consumer->id}}">
    @include('global.forms._ajax-errors', ['e_pre' => 'update-api-consumer-id'])
</div>
<!-- Email Field -->
<div class="form-group" id="update-api-consumer-email">
    <label for="email" class="col-sm-2 control-label">Email:</label>
    <div class="col-sm-10">
        <input type="email"
               name="email"
               value="{{$api_consumer->email}}"
               placeholder="Enter a New Email Address"
               class="form-control"
        >
        @include('global.forms._ajax-errors', ['e_pre' => 'update-api-consumer-email'])
    </div>
</div>
