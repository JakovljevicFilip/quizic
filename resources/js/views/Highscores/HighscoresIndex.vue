<template>
<div class="h-100">
    <!-- HIDE HIGHSCORES CONTATINER IF THERE ARE NO HIGHSCORES -->
    <div class="visible h-100 d-flex flex-column" v-if="highscores.length !== 0">
        <!-- HIGHSCORES -->
        <div class="text-white lead flex-grow-1 p-3 container-index--outer container-index__scroll">
            <div class="container-index__highscores">
                <div v-for="(highscore, index) in highscores" :key="index" class="animated slideInDown">
                    <div class="lead p-2 mb-3 row wrapper">
                        <div class="col-1">{{ index+1 }}.</div>
                        <div class="col-10">{{ highscore.username }}</div>
                        <div class="col-1">{{highscore.score}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Loading v-else></Loading>
</div>
</template>

<script>
import Loading from '../Loading';
export default {
    components: {
        Loading
    },
    data(){
        return {
            highscores: [],
        }
    },
    methods: {
        getHighscores(){
             this.$http.get('highscores')
            .then(response =>{
                this.highscores = response.body.highscores;
            })
            .catch(error => {});
        }
    },
    created(){
        this.getHighscores();
    }
}
</script>

<style>

</style>
