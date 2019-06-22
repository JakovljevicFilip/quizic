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
            // SET REFERENCE TO THE HTML ELEMENT
            this.answeredElement = this.$refs.answers[index];
            // ANSWERS ARE NO LONGER CLICKABLE
            this.disabled = true;
            // CALL FOR answered BUS METHOD ON Game
            // PASS USER'S ANSWER
            EventBus.$emit('answered', answer);
        },

        colorTheAnswer(status, correctAnswer){
            // REFERNCE THE HTML ELEMENT CLASS LIST
            let classList = this.answeredElement.classList;
            // ANSWER IS CORRECT
            if(status){
                // COLOR THE CORRECT ANSWER
                classList.add('game-answer--correct');
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
        }, 10000);


        EventBus.$on('showAnswers', this.showAnswers);
        EventBus.$on('colorTheAnswer', data => {
            this.colorTheAnswer(data.status, data.correctAnswer);
        });
        EventBus.$on('hideIncorrectAnswers', data => {
            this.hideIncorrectAnswers(data.incorrectAnswers);
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
