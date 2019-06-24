<template>
    <!-- USED TO HIDE PARTS OF ANIMATION THAT ARE OUTSIDE OF CONTAINER BLOCK -->
    <div class="overflow-hidden">
        <div class="animated slideInUp">
                <h3 class="text-center text-white pt-3 heading">Register</h3>
                <form v-on:submit.prevent="registerController"  autocomplete="off">
                    <label for="username" class="text-white authentication__label" >Enter username:</label>
                    <input type="text"
                        name="username"
                        class="form-control"
                        v-model="username"
                        v-validate="rules.username"
                        autocomplete="off">
                    <span class="d-block text-danger">{{ errors.first('username') }}</span>

                    <label for="password" class="text-white authentication__label">Enter password:</label>
                    <!-- ref IS NEEDED FOR PASSWORD CONFIRM FUNCTIONALITY -->
                    <input type="password"
                        name="password"
                        class="form-control"
                        ref="password"
                        v-model="password"
                        v-validate="rules.password"
                        autocomplete="off">
                    <span class="d-block text-danger">{{ errors.first('password') }}</span>

                    <label for="passwordConfirm" class="text-white authentication__label">Confirm password:</label>
                    <input type="password"
                        name="passwordConfirm"
                        class="form-control"
                        v-model="passwordConfirm"
                        v-validate="rules.passwordConfirm"
                        autocomplete="off">
                    <span class="d-block text-danger">{{ errors.first('passwordConfirm') }}</span>


                    <label for="email" class="text-white authentication__label">Enter email:</label>
                    <input type="email"
                        name="email"
                        class="form-control"
                        v-model="email"
                        v-validate="rules.email"
                        autocomplete="off">
                    <span class="d-block text-danger">{{ errors.first('email') }}</span>

                    <div>
                        <button class="mt-2 mx-auto btn btn-main" :disabled="errors.any()">Register</button>
                    </div>
                </form>

        </div>
    </div>
</template>
<script>
	export default({
		data(){
			return {
				// FIELDS
				username:'',
				password:'',
				passwordConfirm:'',
				email:'',
				// VEE VALIDATION, VALIDATION RULES
				rules:{
					username:'required|alpha_num|min:6',
					// target - CONFIRMED SHOULD LOOK AT THIS
					password:'required|alpha_num|min:6',
					// confirmed - SHOULD HAVE THE SAME VALUE AS REF
					passwordConfirm:'required|confirmed:password',
					email:'required|email',
				}
			}
		},
		methods:{
			// DETERMINES IF REGISTRATION SHOULD BE ALLOWED
			registerController(){
				// RUNS VALIDATION
				this.$validator.validate().then(valid => {
					// VALIDATION PASSED
					if (valid) {
						this.register();
					}
					// VALIDATION DIDN'T PASS
					else{
						// SHOW ERROR MESSAGES BENEATH INPUT FIELDS
                        this.$validator.validateAll();
                        // SHOW ALERT
                        let message = this.$validator.errors.all()[0];
                        this.$swal('Register', message, 'error');
					}
				});
			},

			// REGISTERS USER
			register(){
				this.$auth.register({
					params:{
						username:this.username,
						password:this.password,
						// pasword_confirmation HAS TO BE CALLED THIS WAY SO THAT BACK-END VALIDATION COULD WORK
						password_confirmation:this.passwordConfirm,
						email:this.email
					}
                });
            }
		}
	});
</script>
