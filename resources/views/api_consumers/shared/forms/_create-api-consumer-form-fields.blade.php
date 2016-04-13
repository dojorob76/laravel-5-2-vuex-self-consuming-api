<!-- Shared Fields for the Create API Consumer Form -->
{{ csrf_field() }}
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
        <div class="errlist create-api-consumer-email-error-msg">
            <ul style="margin-bottom: 0"></ul>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Generate Token</button>
</div>