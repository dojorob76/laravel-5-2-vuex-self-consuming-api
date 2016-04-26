<template id="authentication-template">
    <app-login v-show.sync="showAppLogin"
               transition="bounce-down-up"
               class="animated"
    >
    </app-login>
    <app-register v-show.sync="showAppRegister"
                  transition="bounce-down-up"
                  class="animated"
    >
    </app-register>
</template>
@include('authentication.vue_templates._app-register-template')
@include('authentication.vue_templates._app-login-template')