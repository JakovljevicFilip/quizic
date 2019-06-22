<template>
<div class="d-flex justify-content-center animated slideInDown container-game--transparent game-hints">
    <div class="container-hint d-flex justify-content-center">
        <!-- HALF -->
        <button class="btn-hint mr-5" @click="hintController(1)" :disabled="disabled.half || used.half">
            <i class="fas fa-times"></i>
        </button>

        <!-- CHANGE -->
        <button class="btn-hint mr-5" @click="hintController(2)" :disabled="disabled.change || used.change">
            <i class="fas fa-exchange-alt"></i>
        </button>

        <!-- SOLVE -->
        <button class="btn-hint" @click="hintController(3)" :disabled="disabled.solve || used.solve">
            <i class="fas fa-check"></i>
        </button>
    </div>
</div>
</template>

<script>
import {EventBus} from '../../app';

export default {
    data(){
        return {
            disabled: {
                half: true,
                change: true,
                solve: true,
            },

            used: {
                half: false,
                change: false,
                solve: false,
            },
        }
    },
    methods: {
        hintController(hint){
            // DISABLE HINTS
            this.disableHints();

            // HALF
            if(hint === 1){
                this.half();
            }
            // CHANGE
            else if(hint === 2){
                this.change();
            }
            // SOLVE
            else{
                this.solve();
            }
        },

        half(){
            // HINT HALF IS USED
            this.used.half = true;
            // RUN hintHalf BUS METHOD ON Game
            EventBus.$emit('hintHalf');
        },

        change(){

        },

        solve(){

        },

        enableHints(){
            this.disabled.half = false;
            this.disabled.change = false;
            this.disabled.solve = false;
        },

        disableHints(){
            this.disabled.half = true;
            this.disabled.change = true;
            this.disabled.solve = true;
        },
    },

    created(){
        // EventBus METHODS
        EventBus.$on('enableHints', this.enableHints);
        EventBus.$on('disableHints', this.disableHints);
    },
}
</script>

<style>

</style>
