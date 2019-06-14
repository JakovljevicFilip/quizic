<template>
<div class="answer-grid mt-5 p-3">
    <!-- ANSWER -->
    <button v-for="(answer, index) in answers"
        :key="answer.id"
        class="game-answer"
        :class="'game-appear-'+addAnimationClass(index)"
        ref="answers"
        :disabled="disabled"
        @click="answerTheQuestion(answer, index)">{{answer.text}}</button>
</div>
</template>

<script>
// EVENT BUS
import {EventBus} from '../../app';

export default {
    // PASSED FROM GameQuestion
    props: ['answers'],
    data(){
        return {
            // REFERENCE TO ANSWER HTML ELEMENT
            answeredElement: {},
            // CLICKABLE
            disabled: true,
            // USED FOR CLEARING TIMEOUT
            timeout: {},
        }
    },
    methods:{
        addAnimationClass(index){
            // ADDS CLASS WITH A CALL TO A CSS ANIMATION
            return index + 2;
        },

        answerTheQuestion(answer, index){
            // SET REFERENCE TO THE HTML ELEMENT
            this.answeredElement = this.$refs.answers[index];
            // ANSWERS ARE NO LONGER CLICKABLE
            this.disabled = true;
            // CALL FOR answered BUS METHOD ON Game
            // PASS USER'S ANSWER ANSWER
            EventBus.$emit('answered', answer);
        },

        colorTheAnswer(status){
            // REFERNCE THE HTML ELEMENT CLASS LIST
            let classList = this.answeredElement.classList;
            // COLOR THE ANSWER APPROPRIATELY
            status ? classList.add('game-answer--correct') : classList.add('game-answer--incorrect');
        },
    },

    created(){
        // DELAY FOR 10 SECONDS
        // TIME IT TAKES FOR ALL OF THE ANIMATIONS TO RUN
        this.timeout  = setTimeout(() => {
            // ANSWERS ARE CLIKCABLE
            this.disabled = false;
            // RUN startTheTimer BUS METHOD ON GameTime
            EventBus.$emit('startTheTimer');
        }, 10000);


        EventBus.$on('showAnswers', this.showAnswers);
        EventBus.$on('colorTheAnswer', data => {
            this.colorTheAnswer(data.status);
        });
    },

    beforeDestroy(){
        // CLEAR TIMEOUT IF IT'S IN PROGRESS
        clearTimeout(this.timeout);
    }

}
</script>

<style>

</style>
