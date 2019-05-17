<template>
    <div class="app-wrapper">
        <div class="text-center pb-5 item animated fadeInDownBig logo-animation-delay">
            <img src="/img/logo.png" alt="logo" class="logo-height">
        </div>
        <h3 class="text-center text-white mb-5 heading">Welcome,<br>{{this.$auth.user().username}}</h3>
        <router-link :to="{ name: 'menu.admin' }" class="btn mb-2 mx-auto btn-main">New Game</router-link>
        <router-link :to="{ name: 'menu.admin' }" class="btn mb-2 mx-auto btn-main">Highscores</router-link>
        <router-link :to="{ name: 'menu.admin' }" class="btn mx-auto btn-main">Change password</router-link>
        <div class="text-center mt-4">
            <i class="fas fa-power-off menu-logout" @click="logout"></i>
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
        logout(){
            this.$auth.logout();
        }
    }
}
</script>
<style lang="scss" scoped>
    @import "../../../sass/application/menu.scss";
</style>
