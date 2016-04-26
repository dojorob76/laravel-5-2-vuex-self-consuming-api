<script>
    import AppLogin from './AppLogin.vue';
    import AppRegister from './AppRegister.vue';
    import {setLoggedIn, setLoggedOut} from '../../vuex/actions'

    export default{
        data: function () {
            return {
                showAppLogin: true,
                showAppRegister: false
            }
        },

        vuex: {
            actions: {
                setLoggedIn: setLoggedIn,
                setLoggedOut: setLoggedOut
            }
        },

        template: '#authentication-template',

        components: {
            'app-login': AppLogin,
            'app-register': AppRegister
        },

        events: {
            'appLoginWasClicked': function () {
                var self = this;
                self.displayAppLogin();
            },
            'appRegisterWasClicked': function () {
                var self = this;
                self.displayAppRegister();
            },
            'authFormWasSubmitted': function (form, fields, prefix) {
                var self = this;
                self.processAuthForm(form, fields, prefix);
            }
        },

        methods: {
            displayAppLogin: function () {
                var self = this;
                this.showAppRegister = false;
                setTimeout(function () {
                    self.showAppLogin = true;
                }, 1000);
            },

            displayAppRegister: function () {
                var self = this;
                this.showAppLogin = false;
                setTimeout(function () {
                    self.showAppRegister = true;
                }, 1000);
            },

            processAuthForm: function (form, fields, prefix) {
                formErrors.clear();
                buttonFeedback.formSubmit(form, 'show');

                var self = this;
                this.$http.post(form.attr('action'), fields)
                    .then(function (response) {
                        if (!response.data.jwtoken) {
                            self.setLoggedOut();
                        }
                        else {
                            if (response.data.jwtoken != null && response.data.jwtoken != 'undefined') {
                                self.setLoggedIn(response.data.jwtoken);
                            }
                            else {
                                self.setLoggedOut();
                            }
                        }

                        if (response.data.redirector) {
                            location.replace(response.data.redirector);
                        }
                    })
                    .catch(function (response) {
                        if (response.data.reloader) {
                            location.reload();
                        }
                        else {
                            formErrors.set(response.data, prefix);
                        }
                    })
                    .finally(function () {
                        buttonFeedback.formSubmit(form, 'hide');
                    });
            }
        }
    }
</script>