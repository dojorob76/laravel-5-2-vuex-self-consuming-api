<form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}" id="user-register-form">
    {!! csrf_field() !!}

            <!-- (HIDDEN) TOKEN KEY Field -->
    <div class="form-group" id="user-register-token_key">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <input type="hidden" name="token_key" value="{{csrf_token()}}">
            @include('global.forms._ajax-errors', ['e_pre' => 'user-register-token_key', 'e_class' => 'rounded'])
        </div>
    </div>

    <!-- NAME field -->
    <div class="form-group" id="user-register-name">
        <label class="col-md-4 control-label">Name:</label>

        <div class="col-md-6">
            <input type="text" class="form-control" name="name" value="{{ old('name') }}">
            @include('global.forms._ajax-errors', ['e_pre' => 'user-register-name'])
        </div>
    </div>

    <!-- EMAIL field -->
    <div class="form-group" id="user-register-email">
        <label class="col-md-4 control-label">E-Mail:</label>

        <div class="col-md-6">
            <input type="email" class="form-control" name="email" value="{{ old('email') }}">
            @include('global.forms._ajax-errors', ['e_pre' => 'user-register-email'])
        </div>
    </div>

    <!-- PASSWORD field -->
    <div class="form-group" id="user-register-password">
        <label class="col-md-4 control-label">Password:</label>

        <div class="col-md-6">
            <input type="password" class="form-control" name="password">
            @include('global.forms._ajax-errors', ['e_pre' => 'user-register-password'])
        </div>
    </div>

    <!-- PASSWORD CONFIRMATION field -->
    <div class="form-group" id="user-register-password_confirmation">
        <label class="col-md-4 control-label">Confirm Password:</label>

        <div class="col-md-6">
            <input type="password" class="form-control" name="password_confirmation">
            @include('global.forms._ajax-errors', ['e_pre' => 'user-register-password_confirmation'])
        </div>
    </div>

    <!-- SUBMIT Button -->
    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">
            <button type="submit" class="btn btn-primary ajax-auth" data-prefix="user-register-">
                @include('global.partials._button-wait')
                <span class="submit-text" data-wait="Creating Account...">
                    <span class="glyphicon glyphicon-user"></span> Register
                </span>
            </button>
        </div>
    </div>
</form>