<template>
    <div class="h-100 d-flex">
        <div class="m-auto container-menu">
            <!-- LOGO -->
            <div class="text-center pb-5">
                <img src="/img/logo.png" alt="logo" class="logo--height">
            </div>

            <!-- HEADING -->
            <h3 class="text-center text-white mb-3 heading">Change Password</h3>

            <form v-on:submit.prevent="validate"  autocomplete="off">

                <!-- PASSWORD INPUTS -->
                <div class="my-3 position-relative"
                    v-for="(password, index) in passwords"
                    :key="index">
                    <!-- INPUT FIELD -->
                    <input :name="index"
                        class="lead px-1 form-control w-100"
                        :placeholder="password.placeholder"
                        :type="password.type"
                        :ref="index"
                        v-model="password.text"
                        v-validate="rules[index]"
                        autocomplete="off">

                    <!-- SHOW ICON -->
                    <div class="position-absolute d-flex icon__fixed-top-right" v-if="password.text !== ''">
                        <!-- SHOW -->
                        <i class="fas fa-eye m-auto icon icon__eye"
                            v-tooltip.bottom="'Show text'"
                            v-if="password.type === 'password'"
                            @click="password.type = 'text'">
                        </i>
                        <!-- HIDE -->
                        <i class="fas fa-eye-slash m-auto icon icon__eye"
                            v-tooltip.bottom="'Hide text'"
                            v-if="password.type === 'text'"
                            @click="password.type = 'password'">
                        </i>

                    </div>
                </div>

                <!-- CONFIRM BUTTON -->
                <button class="btn mx-auto btn-main">Confirm</button>

            </form>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            passwords: {},
            // VEE VALIDATION, VALIDATION RULES
            rules:{
                current:'required|regex:^([A-Za-z0-9_])+$|min:8|max:20',
                new:'required|regex:^([A-Za-z0-9_])+$|min:8|max:20',
                // confirmed - PASSWORD SHOULD MATCH VALUE OF new
                // WORKS THROUGH ref
                confirm:'required|confirmed:new',
            }
        }
    },
    methods: {
        setPasswordsObject(){
            this.passwords = {
                // INPUT FIELDS
                current: {
                    text: '',
                    type: 'password',
                    placeholder: 'Enter current password',
                },
                new: {
                    text: '',
                    type: 'password',
                    placeholder: 'Enter new password',
                },
                confirm: {
                    text: '',
                    type: 'password',
                    placeholder: 'Confirm new password',
                },
            };
        },

        validate(){
            // RUNS VALIDATION
            this.$validator.validate().then(valid => {
                // VALIDATION PASSED
                if (valid) {
                    // CURRENT AND NEW PASSWORD ARE DIFFERENT
                    if( this.passwords.current.text != this.passwords.new.text){
                        this.changePassword();
                    }
                    else{
                        let message = 'Current and new password are the same.';
                        this.$swal('Change Password', message, 'error');
                    }
                }
                // VALIDATION DIDN'T PASS
                else{
                    // SHOW ERROR MESSAGES BENEATH INPUT FIELDS
                    this.$validator.validateAll();
                    // SHOW ALERT
                    let message = this.$validator.errors.all()[0];
                    this.$swal('Change Password', message, 'error');
                }
            });
        },

        changePassword(){
            // SEND CHANGE PASSWORD REQUEST
            this.$http.patch('users/password',{
                password_compare: this.passwords.current.text,
                password: this.passwords.new.text,
                password_confirmation: this.passwords.confirm.text,
            })
            .then(response => {
                // RESET PASSWORDS OBJECT
                this.setPasswordsObject();
            })
            .catch(error => {})
        },
    },

    created(){
        // INITIATE PASSWORDS OBJECT
        this.setPasswordsObject();
    }
}
</script>

<style>

</style>
