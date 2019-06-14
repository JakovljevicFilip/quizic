<template>
<div class="text-center position-relative game-margin-large">
    <!-- TIMER -->
    <div class="animated zoomIn game-time">
        {{time}}
    </div>
    <!-- TIMER ANIMATION -->
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
// EVENT BUS
import {EventBus} from '../../app';

export default {
    props: ['username', 'score'],
    watch:{
        // WATCH TIME VALUE
        time(value){
            // IF VALUE OF TIME IS 0
            if(value === 0){
                // TIME IS UP
                this.timeIsUp();
            }
        }
    },
    data(){
        return {
            // TIME STARTS FROM
            timeStart: 10,
            // CURRENT TIME
            time: 10,
            // TOGGLE TIMER ANIMATION
            gameIsInProgress: false,
            // REFERENCE TO INTERVAL
            interval: {},
        }
    },
    methods: {
        startTheTimer(){
            // SHOW TIMER ANIMATION
            this.gameIsInProgress = true;
            // START TIMER
            this.interval = setInterval(()=>{
                this.time--;
            }, 1000);
        },

        stopTheTimer(){
            // STOP TIMER
            clearInterval(this.interval);
            // HIDE TIMER ANIMATION
            this.gameIsInProgress = false;
        },

        timeIsUp(){
            // STOP TIMER
            this.stopTheTimer();
            // SHOW MODAL
            this.showModal();
        },

        showModal(){
            // RUN showGameModal BUS METHOD ON Game
            EventBus.$emit('showGameModal',{
                // MODAL SETUP
                title: 'Time\'s up!',
            });
        },

        resetTheTimer(){
            // REVERT TIMER BACK TO ORIGINAL
            this.time = this.timeStart;
        },

    },

    created(){
        // BUSS METHODS
        EventBus.$on('startTheTimer',this.startTheTimer);

        EventBus.$on('stopTheTimer', this.stopTheTimer);

        EventBus.$on('resetTheTimer', this.resetTheTimer);
    },

    beforeDestroy(){
        // CLEAR INTERVAL IF IT'S RUNNING
        this.stopTheTimer();
    },
}
</script>

<style>

</style>
