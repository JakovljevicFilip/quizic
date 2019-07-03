<template>
    <div class="text-center my-3" v-if="show">
        <img src="/img/logo.png" alt="logo" class="logo--height">
    </div>
</template>

<script>
export default {
    computed: {
        // CURRENT ROUTE
        name: function(){
            return this.$route.name;
        },
        // BACK IS SHOWN/HIDDEN
        show: function(){
            // CURRENT ROUTE EXISTS IN show ARRAY
            return this.showOn[this.name] !== undefined;
        }
    },
    data(){
        return{
            // PAGES ON WHICH LOGO IS SUPPOSSED TO BE SHOWN
            showOn: {
                'questions.index': true,
                'questions.create': true,
                'users.index': true,
                'highscores': true
            },
        }
    },
}
</script>

<style>

</style>
