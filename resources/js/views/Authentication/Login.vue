<template>
<div class="overflow-hidden">
    <div class="animated slideInDown">
		<h3 class="text-center text-white pt-3 heading">Login</h3>
        <form v-on:submit.prevent="loginController"  autocomplete="off">
            <!-- USERNAME -->
            <div>
                <!-- LABEL -->
                <label for="username" class="text-white authentication__label" >Enter username:</label>

                <!-- INPUT -->
                <input type="text"
                    name="username"
                    class="form-control"
                    v-model="username"
                    v-validate="rules.username"
                    autocomplete="off">
                <span class="d-block text-danger">{{ errors.first('username') }}</span>
            </div>

            <!-- PASSWORD -->
            <div>
                <!-- LABEL -->
                <label for="password" class="text-white authentication__label">Enter password:</label>

                <!-- INPUT -->
                <div class="position-relative">
                    <!-- INPUT FIELD -->
                    <input name="password"
                        class="lead px-1 form-control w-100"
                        :placeholder="password.placeholder"
                        :type="password.type"
                        v-model="password.text"
                        v-validate="rules.password"
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

                    <!-- ERROR MESSAGE -->
                    <div class="text-danger">{{ errors.first('password') }}</div>
                </div>

            </div>

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
				password: {
                    text: 'Quizic123',
                    type: 'password',
                    placeholder: 'Enter password',
                },
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
						password:this.password.text,
					}
                });
            }
		}
	});
</script>
