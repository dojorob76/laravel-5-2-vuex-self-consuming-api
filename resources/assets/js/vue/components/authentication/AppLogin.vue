<script>
    export default{
        data: function () {
            return {
                appLoginFields: {
                    token_key: appGlobals.csrf,
                    email: null,
                    password: null,
                    remember: false
                }
            }
        },

        template: '#app-login-template',

        methods: {
            activateAppRegister: function () {
                this.resetAppLoginForm();
                this.$dispatch('appRegisterWasClicked');
            },

            submitAppLoginForm: function () {
                var self = this;
                var form = $('#user-login-form');
                var fields = self.appLoginFields;
                var prefix = 'user-login-';

                // In case the 'These credentials do not match our records' msg is showing...
                $('.flash-message').addClass('hide-it');

                this.$dispatch('authFormWasSubmitted', form, fields, prefix);
            },

            resetAppLoginForm: function () {
                formErrors.clear();
                var self = this;
                self.appLoginFields.token_key = appGlobals.csrf;
                self.appLoginFields.email = null;
                self.appLoginFields.password = null;
                self.appLoginFields.remember = null;
            }
        }
    }
</script>