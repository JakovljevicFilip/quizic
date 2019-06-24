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
            // USED TO COLOR CORRECT ANSWER IN CASE ANSWER IS INCORRECT
            correctAnswer: null,
        }
    },
    methods:{
        addAnimationClass(index){
            // ADDS CLASS WITH A CALL TO A CSS ANIMATION
            return index + 2;
        },

        answerTheQuestion(answer, index){
            // DISABLE ANSWERS
            this.disableAnswers();
            // CALL FOR answered BUS METHOD ON Game
            // PASS USER'S ANSWER
            EventBus.$emit('answered', answer);

            // RUN disableHints BUS METHOD ON GameHints
            EventBus.$emit('disableHints');
        },

        disableAnswers(){
            // ANSWERS ARE NO LONGER CLICKABLE
            this.disabled = true;
        },

        colorTheAnswer(status, correctAnswer){
            // ANSWER IS CORRECT
            if(status){
                // FIND THE CORRECT ANSWER
                this.colorTheCorrectAnswer(correctAnswer);
            }
            else{
                // FIND THE CORRECT ANSWER
                this.colorTheCorrectAnswer(correctAnswer);
                // COLOR THE INCORRECT ANSWER
                classList.add('game-answer--incorrect');
            }

        },

        colorTheCorrectAnswer(correctAnswer){
            for(let i=0; i < this.answers.length; i++){
                if(this.answers[i].id == correctAnswer){
                    this.$refs.answers[i].classList.add('game-answer--correct');
                    break;
                }
            }
        },

        hideIncorrectAnswers(incorrectAnswers){
            for(let i=0; i < this.answers.length; i++){
                for(let y in incorrectAnswers){
                    if(this.answers[i].id == incorrectAnswers[y].id){
                        this.$refs.answers[i].classList.add('d-none');
                    }
                }
            }
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
            // RUN enableHints BUS METHOD ON GameHints
            EventBus.$emit('enableHints');
        }, 10000);


        EventBus.$on('showAnswers', this.showAnswers);
        EventBus.$on('colorTheAnswer', data => {
            this.colorTheAnswer(data.status, data.correctAnswer);
        });
        EventBus.$on('hintHalf', data => {
            this.hideIncorrectAnswers(data.incorrectAnswers);
        });
        EventBus.$on('disableAnswers', this.disableAnswers);
    },

    beforeDestroy(){
        // CLEAR TIMEOUT IF IT'S IN PROGRESS
        clearTimeout(this.timeout);
    }

}
</script>

<style>

</style>
