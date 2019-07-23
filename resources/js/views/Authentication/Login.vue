<template>
<div class="overflow-hidden">
    <div class="animated slideInDown">
		<h3 class="text-center text-white pt-3 heading">Login</h3>
        <form v-on:submit.prevent="loginController"  autocomplete="off">
            <label for="username" class="text-white authentication__label" >Enter username:</label>
            <input type="text"
                name="username"
                class="form-control"
                v-model="username"
                v-validate="rules.username"
                autocomplete="off">
            <span class="d-block text-danger">{{ errors.first('username') }}</span>

            <label for="password" class="text-white authentication__label">Enter password:</label>
            <input type="password"
                name="password"
                class="form-control"
                v-model="password"
                v-validate="rules.password"
                autocomplete="off">
            <span class="d-block text-danger">{{ errors.first('password') }}</span>

            <div>
                <button class="mt-2 mx-auto btn btn-main" :disabled="errors.any()">Login</button>
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
				username:'Administrator',
				password:'Quizic123',
				// VEE VALIDATION
				rules:{
					username:'required|regex:^([A-Za-z0-9_])+$|min:8|max:20',
					// target - CONFIRMED SHOULD LOOK AT THIS
					password:'required|regex:^([A-Za-z0-9_])+$|min:8|max:20',
				}
			}
		},
		methods:{
			// DETERMINES IF LOGIN SHOULD BE ALLOWED
			loginController(){
				// RUNS VALIDATION
				this.$validator.validate().then(valid => {
					// VALIDATION PASSED
					if (valid) {
						this.login();
					}
					// VALIDATION DIDN'T PASS
					else{
						// SHOW ERROR MESSAGES BENEATH INPUT FIELDS
                        this.$validator.validateAll();
                        // SHOW ALERT
                        let message = this.$validator.errors.all()[0];
                        this.$swal('Login', message, 'error');
					}
				});
			},

			// LOLGIN USER
			login(){
				this.$auth.login({
                    params:{
						username:this.username,
						password:this.password,
					}
                });
            }
		}
	});
</script>
