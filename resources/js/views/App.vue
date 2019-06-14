<template>
    <div class="app-background">
        <div class="container-app">
            <div class="container-main">
                <router-view></router-view>
            </div>
            <Back></Back>
            <footer class="footer">
                <p class="text-center text-white">© 2019 QUIZZIĆ</p>
            </footer>
        </div>
    </div>
</template>
<script>
// COMPONENTS
import Back from './Back';

export default {
    components: {
        Back
    },
}
</script>
