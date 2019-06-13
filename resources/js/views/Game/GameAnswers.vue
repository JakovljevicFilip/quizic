<template>
<div class="answer-grid mt-5 p-3">
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
import {EventBus} from '../../app';

export default {
    props: ['answers'],
    data(){
        return {
            answeredElement: {},
            disabled: true,
            timeout: {},
        }
    },
    methods:{
        addAnimationClass(index){
            return index + 2;
        },

        answerTheQuestion(answer, index){
            this.answeredElement = this.$refs.answers[index];
            this.disabled = true;
            this.submitAnswer(answer);
        },

        submitAnswer(answer){
            EventBus.$emit('answered', answer);
        },

        colorTheAnswer(status){
            let classList = this.answeredElement.classList;
            status ? classList.add('game-answer--correct') : classList.add('game-answer--incorrect');
        },
    },

    created(){
        this.timeout  = setTimeout(() => {
            this.disabled = false;
            EventBus.$emit('startTheTimer');
        }, 10000);


        EventBus.$on('showAnswers', this.showAnswers);
        EventBus.$on('colorTheAnswer', data => {
            this.colorTheAnswer(data.status);
        });
    },

    beforeDestroy(){
        clearTimeout(this.timeout);
    }

}
</script>

<style>

</style>
