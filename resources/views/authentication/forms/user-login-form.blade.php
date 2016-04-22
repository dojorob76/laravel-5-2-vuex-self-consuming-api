<form class="form-horizontal" role="form" method="POST" action="{{ action('AuthenticationController@postLogin') }}" id="user-login-form">
    {!! csrf_field() !!}

    <!-- (HIDDEN) TOKEN KEY Field -->
    <div class="form-group" id="user-login-token_key">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <input type="hidden" name="token_key" value="{{csrf_token()}}">
            @include('global.forms._ajax-errors', ['e_pre' => 'user-login-token_key', 'e_class' => 'rounded'])
        </div>
    </div>

    <!-- EMAIL Field -->
    <div class="form-group" id="user-login-email">
        <label class="col-md-4 control-label">Email:</label>
        <div class="col-md-6">
            <input type="email" class="form-control" name="email" value="{{ old('email') }}">
            @include('global.forms._ajax-errors', ['e_pre' => 'user-login-email'])
        </div>
    </div>

    <!-- PASSWORD Field -->
    <div class="form-group" id="user-login-password">
        <label class="col-md-4 control-label">Password:</label>
        <div class="col-md-6">
            <input type="password" class="form-control" name="password">
            @include('global.forms._ajax-errors', ['e_pre' => 'user-login-password'])
        </div>
    </div>

    <!-- REMEMBER ME Checkbox -->
    <div class="form-group" id="user-login-remember">
        <div class="col-md-6 col-md-offset-4">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="remember"> Remember Me
                </label>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">
            <button type="submit" class="btn btn-primary ajax-auth" data-prefix="user-login-">
                @include('global.partials._button-wait')
                <span class="submit-text" data-wait="Authorizing...">
                    <span class="glyphicon glyphicon-log-in"></span> Log In
                </span>
            </button>
            <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a>
        </div>
    </div>
</form>