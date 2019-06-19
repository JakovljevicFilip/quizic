<template>
<div class="d-flex h-100">
	<div class="m-auto p-5 container-menu">
			<div class="text-center pb-4 animated fadeInDownBig logo--animation-delay">
				<img src="/img/logo.png" alt="logo" class="logo--height">
			</div>
			<div class="d-flex justify-content-center">
				<router-link :to="{ name: 'login' }" class="flex-grow-1 authentication__toggle" active-class="authentication__toggle--active">Login</router-link>
				<router-link :to="{ name: 'register' }" class="flex-grow-1 authentication__toggle" active-class="authentication__toggle--active">Register</router-link>
			</div>
			<router-view></router-view>
            <router-link :to="{ name: 'game' }" class="text-center d-block text-white mt-1 wrapper">... or try Quizić without logging in.</router-link>
	</div>
</div>
</template>
<script>
export default {
	created() {
        // REMOVES AUTHENTICATION TOKEN ONCE USER GOES TO LOGIN OR REGISTER
        localStorage.removeItem('Authorization');
	}
}
</script>
