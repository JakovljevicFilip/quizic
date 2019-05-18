<template>
	<div class="app-wrapper">
			<div class="text-center pb-5 item animated fadeInDownBig logo-animation-delay">
				<img src="/img/logo.png" alt="logo" class="logo-height">
			</div>
			<div class="d-flex justify-content-center">
				<router-link :to="{ name: 'login' }" class="authentication-toggle flex-grow-1" active-class="authentication-toggle-active">Login</router-link>
				<router-link :to="{ name: 'register' }" class="authentication-toggle flex-grow-1" active-class="authentication-toggle-active">Register</router-link>
			</div>
			<router-view></router-view>
	</div>
</template>
<script>

export default {
	data() {
		return {

		};
	},
	created() {
        // REMOVES AUTHENTICATION TOKEN ONCE USER GOES TO LOGIN OR REGISTER
        localStorage.removeItem('Authorization');
        console.log('Token removed.');
	},
	methods: {

	}
}
</script>
<style lang="scss">
	@import "../../../sass/application/authentication.scss";
</style>
