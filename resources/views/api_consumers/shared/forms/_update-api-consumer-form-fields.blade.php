<!-- Shared Fields for the Update API Consumer Form -->
{{ method_field('PUT') }}
{{ csrf_field() }}
<input type="hidden" name="id" value="{{$api_consumer->id}}">
<!-- Email Field -->
<div class="form-group" id="update-api-consumer-email">
    <label for="email">Email</label>
    <input type="email"
           name="email"
           value="{{$api_consumer->email}}"
           placeholder="Enter a New Email Address"
           class="form-control"
    >
    <div class="errlist update-api-consumer-email-error-msg"><ul class="mb0"></ul></div>
</div>