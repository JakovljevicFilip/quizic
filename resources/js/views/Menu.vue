<template>
	<div class="app-wrapper">
        <div class="text-center pb-5 item animated fadeInDownBig menu-animation-delay">
            <img src="/img/logo.png" alt="logo" class="menu-logo">
        </div>
        <h3 class="text-center mb-5 menu-heading">Dobrodošli Filip!</h3>
		<router-link :to="{ name: 'hello' }" class="btn mb-2 mx-auto menu-btn">New Game</router-link>
        <router-link :to="{ name: 'hello' }" class="btn mb-2 mx-auto menu-btn">Highscores</router-link>
        <router-link :to="{ name: 'hello' }" class="btn mb-2 mx-auto menu-btn">Add Question</router-link>
        <router-link :to="{ name: 'hello' }" class="btn mb-2 mx-auto menu-btn">Delete Question</router-link>
        <router-link :to="{ name: 'hello' }" class="btn mx-auto menu-btn">Edit Users</router-link>
        <div class="text-center mt-4">
            <i class="fas fa-power-off menu-logout"></i>
        </div>
	</div>
</template>
<script>
export default {
    data() {
        return {
            
        };
    },
    created() {

    },
    methods: {
        
    }
}
</script>
<style lang="scss" scoped>
	@import "../../sass/application/menu.scss";
</style>