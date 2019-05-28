<template>
    <div class="container-menu">
        <div class="text-center pb-5">
            <img src="/img/logo.png" alt="logo" class="logo--height">
        </div>
        <h3 class="text-center text-white mb-5 heading">Welcome,<br>{{this.$auth.user().username}}</h3>
        <router-link :to="{ name: 'menu' }" class="btn mb-2 mx-auto btn__main">New Game</router-link>
        <router-link :to="{ name: 'menu' }" class="btn mb-2 mx-auto btn__main">Highscores</router-link>
        <router-link :to="{ name: 'menu' }" class="btn mx-auto btn__main">Change password</router-link>
        <!-- USER IS AN ADMINISTRATOR -->
        <template v-if="this.$auth.user().role === 2">
            <router-link :to="{ name: 'questions.index' }" class="btn mb-2 mx-auto btn__main">Questions</router-link>
            <router-link :to="{ name: 'menu' }" class="btn mx-auto btn__main">Users</router-link>
        </template>
        <div class="text-center mt-4">
            <i class="fas fa-power-off logout" @click="logout"></i>
        </div>
    </div>
</template>
<script>
export default {
    methods: {
        logout(){
            // RUN VUE-AUTH LOGOUT METHOD
            this.$auth.logout();
        }
    }
}
</script>
