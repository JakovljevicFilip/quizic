<template>
<div class="d-flex h-100">
    <div class="m-auto p-5 container-menu">
        <div class="text-center pb-5">
            <img src="/img/logo.png" alt="logo" class="logo--height">
        </div>
        <h3 class="text-center text-white mb-5 heading">Welcome,<br>{{this.$auth.user().username}}</h3>
        <router-link :to="{ name: 'game' }" class="btn mb-2 mx-auto btn-main">New Game</router-link>
        <router-link :to="{ name: 'menu' }" class="btn mb-2 mx-auto btn-main">Highscores</router-link>
        <router-link :to="{ name: 'users.password' }" class="btn mx-auto btn-main">Change password</router-link>
        <!-- USER IS AN ADMINISTRATOR -->
        <template v-if="this.$auth.user().role === 2">
            <router-link :to="{ name: 'questions.index' }" class="btn mb-2 mx-auto btn-main">Questions</router-link>
            <router-link :to="{ name: 'users.index' }" class="btn mx-auto btn-main">Users</router-link>
        </template>
        <div class="text-center mt-4">
            <i class="fas fa-power-off icon icon__logout" @click="logout"></i>
        </div>
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
