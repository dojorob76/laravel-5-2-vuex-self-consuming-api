<form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
    {!! csrf_field() !!}

    <input type="hidden" name="token" value="{{ $token }}">

    <!-- EMAIL field -->
    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">Email:</label>

        <div class="col-md-6">
            <input type="email" class="form-control" name="email" value="{{ $email or old('email') }}">
            @if ($errors->has('email'))
                <div class="errlist">
                    <ul class="mb0">
                        <li>{{ $errors->first('email') }}</li>
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <!-- PASSWORD field -->
    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">Password:</label>

        <div class="col-md-6">
            <input type="password" class="form-control" name="password">
            @if ($errors->has('password'))
                <div class="errlist">
                    <ul class="mb0">
                        <li>{{ $errors->first('password') }}</li>
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <!-- PASSWORD CONFIRMATION field -->
    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">Confirm Password:</label>

        <div class="col-md-6">
            <input type="password" class="form-control" name="password_confirmation">
            @if ($errors->has('password_confirmation'))
                <div class="errlist">
                    <ul class="mb0">
                        <li>{{ $errors->first('password_confirmation') }}</li>
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <!-- SUBMIT Button -->
    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">
            <button type="submit" class="btn btn-primary">
                <span class="glyphicon glyphicon-refresh"></span> Reset Password
            </button>
        </div>
    </div>
</form>