<template>
    <div>
        <div class="app-background">
            <router-view></router-view>
        </div>
        <footer class="app-footer">
            <p class="text-center text-white fixed-bottom">© 2019 QUIZZIĆ</p>
        </footer>
    </div>
</template>
<script>
    export default {}
</script>
<style lang="scss" scoped>
    @import "../../sass/application/app.scss";
</style>