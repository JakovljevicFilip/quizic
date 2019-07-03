<template>
    <div class="app-background">
        <div class="container-app">
            <Logo></Logo>
            <div class="container-main">
                <router-view></router-view>
            </div>
            <Back></Back>
            <Modal></Modal>
            <footer class="footer">
                <p class="text-center text-white">© 2019 QUIZZIĆ</p>
            </footer>
        </div>
    </div>
</template>
<script>
// COMPONENTS
import Logo from './Logo';
import Back from './Back';
import Modal from './Modal';

export default {
    components: {
        Logo,
        Back,
        Modal,
    },
}
</script>
