<template>
    <div class="h-100 d-flex flex-column">
        <template v-if="questionRecieved">
            <GameLogo></GameLogo>
            <GameHints></GameHints>
            <GameInfo :username="username" :score="score"></GameInfo>
            <GameTime></GameTime>
            <GameQuestion v-if="questionRecieved" :question="question" :key="question.text"></GameQuestion>
        </template>
        <Loading v-else></Loading>
        <GameModal :username="username" :score="score"></GameModal>
    </div>
</template>

<script>
import GameHints from './GameHints';
import GameLogo from './GameLogo';
import GameInfo from './GameInfo';
import GameTime from './GameTime';
import GameQuestion from './GameQuestion';
import Loading from '../Loading';
import GameModal from './GameModal';
import { EventBus } from '../../app';

export default {
    computed: {
        questionRecieved(){
            return this.question['text'] !== undefined;
        }
    },

    components: {
        GameHints,
        GameLogo,
        GameInfo,
        GameTime,
        GameQuestion,
        Loading,
        GameModal,
    },

    data(){
        return{
            question: {},
            answer: {},
            answerStatus: null,
            gameOverTitle: 'The answer is incorrect!',
            username: 'guest12312',
            score: 0,
        }
    },

    methods:{
        getQuestion(){
            this.question = {
                text: 'Sample text longer question really long question... And even longer, heck it\' really long.',
                answers: [
                    {
                        text: 'Correct answer',
                        status: true,
                    },
                    {
                        text: 'Incorrect answer',
                        status: false,
                    },
                    {
                        text: 'Incorrect answer',
                        status: false,
                    },
                    {
                        text: 'Incorrect answer',
                        status: false,
                    },

                ]
            };
        },

        setNewQuestion(){
            this.question.text = Math.random();
            EventBus.$emit('resetTheTimer');
        },

        startNewGame(){
            this.setNewQuestion();
            EventBus.$emit('resetTheTimer');
        },

        answered(){
            this.stopTheTimer();
            this.submitAnswer();
        },

        submitAnswer(){
            this.answerStatus = this.answer.status;
            this.handleAnswerResponse();
        },

        handleAnswerResponse(){
            this.colorTheAnswer(this.answerStatus);

            setTimeout(this.handleAnswerResponseFurther,3000);
        },

        handleAnswerResponseFurther(){
            if(this.answerStatus){
                this.setNewQuestion();
            }
            else{
                EventBus.$emit('gameModal', {
                    title: this.gameOverTitle,
                });
            }
        },


        stopTheTimer(){
            EventBus.$emit('stopTheTimer');
        },

        colorTheAnswer(status){
            EventBus.$emit('colorTheAnswer', {
                status,
            });
        },
    },

    created(){
        setTimeout(()=>{
            this.getQuestion();
        }, 2000);

        EventBus.$on('startNewGame', this.startNewGame);

        EventBus.$on('answered', answer => {
            this.answer = answer;
            this.answered();
        });
    },


}
</script>

<style>

</style>
