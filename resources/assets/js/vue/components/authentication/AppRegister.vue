<script>
    export default{
        data: function () {
            return {
                appRegisterFields: {
                    token_key: appGlobals.csrf,
                    name: null,
                    email: null,
                    password: null,
                    password_confirmation: null
                }
            }
        },

        template: '#app-register-template',

        methods: {
            activateAppLogin: function () {
                this.resetAppRegisterForm();
                this.$dispatch('appLoginWasClicked');
            },

            submitAppRegisterForm: function () {
                var self = this;
                var form = $('#user-register-form');
                var fields = self.appRegisterFields;
                var prefix = 'user-register-';

                this.$dispatch('authFormWasSubmitted', form, fields, prefix);
            },

            resetAppRegisterForm: function () {
                formErrors.clear();
                var self = this;
                self.appRegisterFields.token_key = appGlobals.csrf;
                self.appRegisterFields.name = null;
                self.appRegisterFields.email = null;
                self.appRegisterFields.password = null;
                self.appRegisterFields.password_confirmation = null;
            }
        }
    }
</script>