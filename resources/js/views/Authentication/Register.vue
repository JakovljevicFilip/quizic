<template>
	<div>
		<h3 class="text-center text-white pt-3 authentication-heading">Register</h3>
		<label for="username" class="authentication-label text-white" >Enter username:</label>
		<input type="text" name="username" class="form-control" v-model="username" v-validate="rules.username">
		<span class="d-block text-danger">{{ errors.first('username') }}</span>

		<label for="password" class="authentication-label text-white">Enter password:</label>
		<!-- ref IS NEEDED FOR PASSWORD CONFIRM FUNCTIONALITY -->
		<input type="password" name="password" class="form-control" ref="password" v-model="password" v-validate="rules.password">
		<span class="d-block text-danger">{{ errors.first('password') }}</span>

		<label for="passwordConfirm" class="authentication-label text-white">Confirm password:</label>
		<input type="password" name="passwordConfirm" class="form-control" v-model="passwordConfirm" v-validate="rules.passwordConfirm">
		<span class="d-block text-danger">{{ errors.first('passwordConfirm') }}</span>


		<label for="email" class="authentication-label text-white">Enter email:</label>
		<input type="email" name="email" class="form-control" v-model="email" v-validate="rules.email">
		<span class="d-block text-danger">{{ errors.first('email') }}</span>


		<button class="btn btn-main mt-2" @click="registerController" :disabled="errors.any()">Register</button>
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
				// VEE VALIDATION
				rules:{
					username:'required|alpha_num|min:6',
					// target - CONFIRMED SHOULD LOOK AT THIS
					password:'required|alpha_num|min:6',
					// confirmed - SHOULD HAVE THE SAME VALUE AS
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
					// PASSES VALIDATION
					if (valid) {
						this.register();
					}
					// DOESN'T PASS VALIDATION
					else{
						// SHOWS VALDIATION MESSAGES
						this.$validator.validateAll();
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
					},
					success: (response)=>{
						alert(response.data.messages);
					},
					error: (error)=>{
						let response=error.response.data;
						// EXPECTED ERROR
						if(!response.status){
							alert(response.messages);
						}
						// UNEXPECTED ERROR
						else{
							alert(error)
						}
					}
				});
			}
		}
	});
</script>