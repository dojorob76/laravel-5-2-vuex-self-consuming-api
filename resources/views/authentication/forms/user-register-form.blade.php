<form class="form-horizontal"
      role="form"
      method="POST"
      action="{{ action('AuthenticationController@postRegister') }}"
      id="user-register-form"
      v-on:submit.prevent="submitAppRegisterForm"
>
    <!-- (HIDDEN) TOKEN KEY Field -->
    <div class="form-group" id="user-register-token_key">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @include('global.forms._ajax-errors', ['e_pre' => 'user-register-token_key', 'e_class' => 'rounded'])
        </div>
    </div>

    <!-- NAME field -->
    <div class="form-group" id="user-register-name">
        <label class="col-md-4 control-label">Name:</label>

        <div class="col-md-6">
            <input type="text"
                   class="form-control"
                   name="name"
                   value="{{ old('name') }}"
                   v-model="appRegisterFields.name"
            >
            @include('global.forms._ajax-errors', ['e_pre' => 'user-register-name'])
        </div>
    </div>

    <!-- EMAIL field -->
    <div class="form-group" id="user-register-email">
        <label class="col-md-4 control-label">E-Mail:</label>

        <div class="col-md-6">
            <input type="email"
                   class="form-control"
                   name="email"
                   value="{{ old('email') }}"
                   v-model="appRegisterFields.email"
            >
            @include('global.forms._ajax-errors', ['e_pre' => 'user-register-email'])
        </div>
    </div>

    <!-- PASSWORD field -->
    <div class="form-group" id="user-register-password">
        <label class="col-md-4 control-label">Password:</label>

        <div class="col-md-6">
            <input type="password"
                   class="form-control"
                   name="password"
                   v-model="appRegisterFields.password"
            >
            @include('global.forms._ajax-errors', ['e_pre' => 'user-register-password'])
        </div>
    </div>

    <!-- PASSWORD CONFIRMATION field -->
    <div class="form-group" id="user-register-password_confirmation">
        <label class="col-md-4 control-label">Confirm Password:</label>

        <div class="col-md-6">
            <input type="password"
                   class="form-control"
                   name="password_confirmation"
                   v-model="appRegisterFields.password_confirmation"
            >
            @include('global.forms._ajax-errors', ['e_pre' => 'user-register-password_confirmation'])
        </div>
    </div>

    <!-- SUBMIT Button -->
    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">
            <feedback-button wait-txt="Creating Account..." btn-type="submit" btn-class="primary">
                <span class="glyphicon glyphicon-user"></span> Register
            </feedback-button>
        </div>
    </div>
</form>