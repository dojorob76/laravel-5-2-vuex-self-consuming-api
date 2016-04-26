<template id="app-register-template">
    <div class="panel panel-default">
        <div class="panel-heading">Register a New {{$site_name}} Account</div>
        <div class="panel-body">@include('authentication.forms.user-register-form')</div>
        <div class="panel-footer text-center">
            <h5 class="text-center">Already have an account?</h5>
            <button v-on:click="activateAppLogin" class="btn btn-large btn-info">
                Log In<span class="hidden-xs"> to {{$site_name}}</span> Now
            </button>
        </div>
    </div>
</template>