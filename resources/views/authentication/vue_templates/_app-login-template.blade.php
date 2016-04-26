<template id="app-login-template">
    <div class="panel panel-default">
        <div class="panel-heading">
            Log In to {{$site_name}}
        </div>
        <div class="panel-body">@include('authentication.forms.user-login-form')</div>
        <div class="panel-footer text-center">
            <h5 class="text-center">Don't have an account yet?</h5>
            <button v-on:click="activateAppRegister" class="btn btn-large btn-info">
                Register<span class="hidden-xs"> a New Account</span> Now
            </button>
        </div>
    </div>
</template>