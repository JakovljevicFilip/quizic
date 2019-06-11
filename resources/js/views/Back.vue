<template>
    <div>
        <div class="text-white text-center" v-if="show">
            <i class="fas fa-long-arrow-alt-left icon icon__back" @click="goBack" alt="back"></i>
        </div>
    </div>
</template>

<script>
export default {
    computed: {
        name: function(){
            return this.$route.name;
        },
        show: function(){
            return this.navigate[this.name] !== undefined;
        }
    },
    data(){
        return{
            navigate: {
                // NAVIGATE FROM (ROUTE NAME) - TO (PATH)
                'questions.index': '/menu',
                'questions.create': '/questions',
                'users.password': '/menu',
                'users.index': '/menu',
            }
        }
    },
    methods: {
        goBack(){
            this.$router.push(this.navigate[this.name]);
        }
    },
}
</script>

<style>

</style>
