<template>
    <div class="h-100 d-flex">
        <div class="m-auto p-5 container-menu">
            <!-- LOGO -->
            <div class="text-center pb-5">
                <img src="/img/logo.png" alt="logo" class="logo--height">
            </div>

            <!-- HEADING -->
            <h3 class="text-center text-white mb-3 heading">Change Password</h3>

            <!-- INPUT DIVS -->
            <div class="my-3 position-relative">
                <!-- INPUT FIELD -->
                <input name="passwordCurrent"
                    class="lead px-1 form-control w-100"
                    placeholder="Enter current password"
                    :type="passwordCurrent.type"
                    v-model="passwordCurrent.text"
                    v-validate="rules.passwordCurrent">

                <!-- SHOW ICON -->
                <div class="position-absolute d-flex icon__fixed-top-right" v-if="passwordCurrent.text !== ''">
                    <!-- SHOW -->
                    <i class="fas fa-eye m-auto icon icon__eye"
                        alt="show"
                        v-if="passwordCurrent.type !== 'text'"
                        @click="passwordCurrent.type = 'text'">
                    </i>
                    <!-- HIDE -->
                    <i class="fas fa-eye-slash m-auto icon icon__eye"
                        alt="hide"
                        v-if="passwordCurrent.type !== 'password'"
                        @click="passwordCurrent.type = 'password'">
                    </i>

                </div>
            </div>

            <div class="my-3 position-relative">
                <input name="passwordNew"
                    class="lead px-1 form-control w-100"
                    placeholder="Enter new password"
                    ref="passwordNew"
                    :type="passwordNew.type"
                    v-model="passwordNew.text"
                    v-validate="rules.passwordNew"
                >

                <div class="position-absolute d-flex icon__fixed-top-right" v-if="passwordNew.text !== ''">
                    <i class="fas fa-eye m-auto icon icon__eye"
                        alt="show"
                        v-if="passwordNew.type !== 'text'"
                        @click="passwordNew.type = 'text'">
                    </i>

                    <i class="fas fa-eye-slash m-auto icon icon__eye"
                        alt="hide"
                        v-if="passwordNew.type !== 'password'"
                        @click="passwordNew.type = 'password'">
                    </i>
                </div>
            </div>

            <div class="my-3 position-relative">
                <input name="passwordConfirm"
                    class="lead px-1 form-control w-100"
                    placeholder="Repeat new password"
                    :type="passwordConfirm.type"
                    v-model="passwordConfirm.text"
                    v-validate="rules.passwordConfirm"
                >

                <div class="position-absolute d-flex icon__fixed-top-right" v-if="passwordConfirm.text !== ''">
                    <i class="fas fa-eye m-auto icon icon__eye"
                        alt="show"
                        v-if="passwordConfirm.type !== 'text'"
                        @click="passwordConfirm.type = 'text'">
                    </i>

                    <i class="fas fa-eye-slash m-auto icon icon__eye"
                        alt="hide"
                        v-if="passwordConfirm.type !== 'password'"
                        @click="passwordConfirm.type = 'password'">
                    </i>
                </div>
            </div>

            <!-- CONFIRM BUTTON -->
            <button class="btn mx-auto btn__main" @click="validate">Confirm</button>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            // INPUT FIELDS
            passwordCurrent: {
                text: '',
                type: 'password',
            },
            passwordNew: {
                text: '',
                type: 'password',
            },
            passwordConfirm: {
                text: '',
                type: 'password',
            },

            // VEE VALIDATION, VALIDATION RULES
            rules:{
                passwordCurrent:'required|alpha_num|min:6',
                // target - CONFIRMED SHOULD LOOK AT THIS
                passwordNew:'required|alpha_num|min:6',
                // confirmed - SHOULD HAVE THE SAME VALUE AS
                passwordConfirm:'required|confirmed:passwordNew',
            }
        }
    },
    methods: {
        validate(){
            // RUNS VALIDATION
            this.$validator.validate().then(valid => {
                // VALIDATION PASSED
                if (valid) {
                    this.changePassword();
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
            this.$http.patch('users/password',{
                password_compare: this.passwordCurrent.text,
                password: this.passwordNew.text,
                password_confirmation: this.passwordConfirm.text,
            })
            .catch(error => {})
        },
    }
}
</script>

<style>

</style>
