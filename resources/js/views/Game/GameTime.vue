<template>
<div class="text-center position-relative game-margin-large">
    <div class="animated zoomIn game-time">
        {{time}}
    </div>
    <div class="position-absolute w-100 game__timer-animation">
        <div class="spinner m-auto" v-if="gameIsInProgress">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
        </div>
    </div>

</div>
</template>

<script>
import {EventBus} from '../../app';

export default {
    watch:{
        time(value){
            if(value === 0){
                this.timeIsUp();
            }
        }
    },
    data(){
        return {
            originalTime: 10,
            time: 10,
            gameIsInProgress: false,
            timeIsUpTitle: 'Time\'s up!',
            interval: {},
        }
    },
    methods: {
        startTheTimer(){
            this.gameIsInProgress = true;
            this.interval = setInterval(()=>{
                this.time--;
            }, 1000);
        },

        clearInterval(){
            clearInterval(this.interval);
        },

        timeIsUp(){
            this.clearInterval();
            this.gameIsInProgress = false;
            EventBus.$emit('gameModal',{
                title: this.timeIsUpTitle,
            });
        },

        resetTheTimer(){
            this.time = this.originalTime;
        },

    },

    created(){
        EventBus.$on('startTheTimer',this.startTheTimer);

        EventBus.$on('stopTheTimer', this.timeIsUp)

        EventBus.$on('resetTheTimer', this.resetTheTimer);
    },

    beforeDestroy(){
        this.clearInterval();
    },
}
</script>

<style>

</style>
