<template>
    <div class="app-wrapper">
        <div class="text-center pb-5 item animated fadeInDownBig logo-animation-delay">
            <img src="/img/logo.png" alt="logo" class="logo-height">
        </div>
        <h3 class="text-center mb-5 menu-heading">Welcome User!</h3>
        <router-link :to="{ name: 'menu.admin' }" class="btn mb-2 mx-auto btn-main">New Game</router-link>
        <router-link :to="{ name: 'menu.admin' }" class="btn mb-2 mx-auto btn-main">Highscores</router-link>
        <router-link :to="{ name: 'menu.admin' }" class="btn mb-2 mx-auto btn-main">Add Question</router-link>
        <router-link :to="{ name: 'menu.admin' }" class="btn mb-2 mx-auto btn-main">Delete Question</router-link>
        <router-link :to="{ name: 'menu.admin' }" class="btn mx-auto btn-main">Edit Users</router-link>
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
    @import "../../../sass/application/menu.scss";
</style>