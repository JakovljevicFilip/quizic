<template>
    <!-- USED TO HIDE PARTS OF ANIMATION THAT ARE OUTSIDE OF CONTAINER BLOCK -->
    <div class="overflow-hidden">
        <div class="animated slideInUp">
                <h3 class="text-center text-white pt-3 heading">Register</h3>
                <form v-on:submit.prevent="registerController"  autocomplete="off">
                    <!-- USERNAME -->
                    <div>
                        <!-- LABEL -->
                        <label for="username" class="text-white authentication__label" >Enter username:</label>

                        <!-- INPUT FIELD -->
                        <input type="text"
                            name="username"
                            class="form-control"
                            v-model="username"
                            v-validate="rules.username"
                            placeholder="Enter username"
                            autocomplete="off">

                        <!-- ERROR MESSSAGE -->
                        <span class="d-block text-danger">{{ errors.first('username') }}</span>
                    </div>

                    <!-- PASSWORD -->
                    <div>
                        <!-- LABEL -->
                        <label for="password" class="text-white authentication__label" >Enter password:</label>

                        <!-- INPUT -->
                        <div class="position-relative">
                            <!-- INPUT FIELD -->
                            <input name="password"
                                class="lead px-1 form-control w-100"
                                :placeholder="password.placeholder"
                                :type="password.type"
                                ref="password"
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
                        </div>

                        <!-- ERROR MESSAGE -->
                        <span class="d-block text-danger">{{ errors.first('password') }}</span>
                    </div>

                    <!-- PASSWORD CONFIRM -->
                    <div>
                        <!-- LABEL -->
                        <label for="passwordConfirm" class="text-white authentication__label" >Confirm password:</label>

                        <!-- INPUT -->
                        <div class="position-relative">
                            <!-- INPUT FIELD -->
                            <input name="passwordConfirm"
                                class="lead px-1 form-control w-100"
                                :placeholder="passwordConfirm.placeholder"
                                :type="passwordConfirm.type"
                                v-model="passwordConfirm.text"
                                v-validate="rules.passwordConfirm"
                                autocomplete="off">

                            <!-- SHOW ICON -->
                            <div class="position-absolute d-flex icon__fixed-top-right" v-if="passwordConfirm.text !== ''">
                                <!-- SHOW -->
                                <i class="fas fa-eye m-auto icon icon__eye"
                                    v-tooltip.bottom="'Show text'"
                                    v-if="passwordConfirm.type === 'password'"
                                    @click="passwordConfirm.type = 'text'">
                                </i>
                                <!-- HIDE -->
                                <i class="fas fa-eye-slash m-auto icon icon__eye"
                                    v-tooltip.bottom="'Hide text'"
                                    v-if="passwordConfirm.type === 'text'"
                                    @click="passwordConfirm.type = 'password'">
                                </i>
                            </div>
                        </div>

                        <!-- ERROR MESSAGE -->
                        <span class="d-block text-danger">{{ errors.first('passwordConfirm') }}</span>
                    </div>

                    <!-- EMAIL -->
                    <div>
                        <!-- LABEL -->
                        <label for="email" class="text-white authentication__label">Enter email:</label>

                        <!-- INPUT -->
                        <input type="email"
                            name="email"
                            class="form-control"
                            v-model="email"
                            v-validate="rules.email"
                            placeholder="Enter "
                            autocomplete="off">

                        <!-- ERROR MESSAGE -->
                        <span class="d-block text-danger">{{ errors.first('email') }}</span>
                    </div>

                    <!-- BUTTON -->
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
                password: {
                    text: '',
                    type: 'password',
                    placeholder: 'Enter password',
                },
				passwordConfirm: {
                    text: '',
                    type: 'password',
                    placeholder: 'Confirm password',
                },
				email:'',
				// VEE VALIDATION, VALIDATION RULES
				rules:{
					username:'required|regex:^([A-Za-z0-9_])+$|min:8|max:20',
					// target - CONFIRMED SHOULD LOOK AT THIS
					password:'required|regex:^([A-Za-z0-9_])+$|min:8|max:20',
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
						password:this.password.text,
						// pasword_confirmation HAS TO BE CALLED THIS WAY SO THAT BACK-END VALIDATION COULD WORK
						password_confirmation:this.passwordConfirm.text,
						email:this.email
					}
                });
            }
		}
	});
</script>
