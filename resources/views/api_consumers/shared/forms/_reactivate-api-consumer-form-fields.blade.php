<!-- Shared Fields for the Reactivate API Consumer Form -->
{{ csrf_field() }}
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
        <div class="errlist reactivate-api-consumer-email-error-msg"><ul class="mb0"></ul></div>
    </div>
    <button type="submit" class="btn btn-primary">Try Again</button>
</div>