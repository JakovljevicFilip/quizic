<template>
<div class="h-100">
    <div class="d-flex flex-column">
        <!-- TITLE -->
        <h1 class="lead text-white text-center display-4">Highscores</h1>
        <!-- HIDE HIGHSCORES CONTATINER IF THERE ARE NO HIGHSCORES -->
        <div class="text-white lead flex-grow-1 container-index--outer container-index__scroll" v-if="highscores.length !== 0">
            <div class="pr-3 container-index__highscores">
                <!-- HIGHSCORES -->
                <div v-for="(highscore, index) in highscores" :key="index" class="animated slideInDown">
                    <div class="lead p-2 mb-3 row wrapper">
                        <div class="col-1">{{ index+1 }}.</div>
                        <div class="col-10">{{ highscore.username }}</div>
                        <div class="col-1">{{highscore.score}}</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- LOADING SCREEN -->
        <Loading v-else></Loading>
    </div>
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
