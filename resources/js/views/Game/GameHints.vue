<template>
<div class="d-flex justify-content-center animated slideInDown container-game--transparent game-hints">
    <div class="container-hint d-flex justify-content-center">
        <!-- HALF -->
        <button class="btn-hint mr-5" @click="hintController('half')" :disabled="disabled.half || used.half">
            <i class="fas fa-times"></i>
        </button>

        <!-- CHANGE -->
        <button class="btn-hint mr-5" @click="hintController('switch')" :disabled="disabled.switch || used.switch">
            <i class="fas fa-exchange-alt"></i>
        </button>

        <!-- SOLVE -->
        <button class="btn-hint" @click="hintController('solve')" :disabled="disabled.solve || used.solve">
            <i class="fas fa-check"></i>
        </button>
    </div>
</div>
</template>

<script>
import {EventBus} from '../../app';

export default {
    props: ['hash'],
    data(){
        return {
            disabled: {
                half: true,
                switch: true,
                solve: true,
            },

            used: {
                half: false,
                switch: false,
                solve: false,
            },

            hint: {},
        }
    },
    methods: {
        hintController(hint){
            // DISABLE HINTS
            this.disableHints();
            // SET HINT AS USED
            this.used[hint] = true;
            // CALL HINT METHOD
            this.useHint(hint);
        },

        enableHints(){
            this.disabled.half = false;
            this.disabled.switch = false;
            this.disabled.solve = false;
        },

        disableHints(){
            this.disabled.half = true;
            this.disabled.switch = true;
            this.disabled.solve = true;
        },

        useHint(hint){
            this.$http.get('game/hint',{
                params: {
                    hint: {
                        text: hint,
                        hash: this.hash,
                    }
                }
            })
            .then(response =>{
                this.hint = response.body.hint;

                if(hint === 'half'){
                    this.handlehalf();
                }
                else if(hint === 'solve'){
                    this.handleSolve();
                }
                else if(hint === 'switch'){
                    this.handleSwitch();
                }
            })
            .catch(error => {});
        },

        handlehalf(){
            EventBus.$emit('hintHalf', {
                incorrectAnswers: this.hint,
            });
        },

        handleSolve(){
            EventBus.$emit('disableAnswers');
            EventBus.$emit('answered', this.hint);
        },

        handleSwitch(){
            EventBus.$emit('hintSwitch', {
                question: this.hint
            });
        }
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
