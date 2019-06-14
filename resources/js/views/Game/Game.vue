<template>
    <div class="h-100 d-flex flex-column">
        <!-- GAME SCREEN -->
        <template v-if="questionRecieved">
            <GameLogo></GameLogo>
            <GameHints></GameHints>
            <GameInfo :username="username" :score="score"></GameInfo>
            <GameTime></GameTime>
            <GameQuestion v-if="questionRecieved" :question="question" :key="question.text"></GameQuestion>
        </template>
        <!-- LOADING SCREEN -->
        <Loading v-else></Loading>
        <!-- MODAL -->
        <GameModal :username="username" :score="score"></GameModal>
    </div>
</template>

<script>
// COMPONENTS
import GameHints from './GameHints';
import GameLogo from './GameLogo';
import GameInfo from './GameInfo';
import GameTime from './GameTime';
import GameQuestion from './GameQuestion';
import Loading from '../Loading';
import GameModal from './GameModal';

// EVENT BUS
import { EventBus } from '../../app';

export default {
    computed: {
        questionRecieved(){
            return this.question['text'] !== undefined;
        }
    },

    components: {
        // SOLVE, SWITCH, 50:50
        GameHints,
        // LOGO
        GameLogo,
        // USERNAME AND SCORE
        GameInfo,
        // QUESTION TIMER
        GameTime,
        // QUESTION
        GameQuestion,
        // POPUP MESSAGES
        GameModal,
        // LOADING SCREEN
        Loading,
    },

    data(){
        return{
            // QUESTION OBJECT
            question: {},
            // USERS ANSWER TO A QUESTION
            answer: {},
            // ANSWER IS CORRECT/INCORRECT
            answerStatus: null,
            // PLAYING AS
            username: 'guest12312',
            // CORRECT QUESTIONS SO FAR
            score: 0,
            modalInformations: {
                // SET ON CALL
                title: '',
                type: 'error',
                confirmButtonText: 'New Game',
                cancelButtonText: 'Quit',
                // SET ON CALL
                html: '',
                origin: 'showGameModal',
            },
        }
    },

    methods:{
        getQuestion(){
            // RUN GET QUESTION REQUEST
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
            // CHANGES QUESTION TEXT SO THAT ENTIRE QUESTION COMPONENT WOULD RE-RENDER
            // USED FOR TESTING
            this.question.text = Math.random();
            // RUN resetTheTimer METHOD IN GameTime
            EventBus.$emit('resetTheTimer');
        },

        startNewGame(){
            // GET NEW QUESTION
            this.setNewQuestion();
        },

        answered(){
            // STOPS TIMER
            this.stopTheTimer();
            // SUBMITS ANSWER
            this.submitAnswer();
        },

        submitAnswer(){
            // QUESTION IS CORRECT/INCORRECT
            this.answerStatus = this.answer.status;
            // HANDLE RESPONSE
            this.handleAnswerResponse();
        },

        handleAnswerResponse(){
            // COLOR THE ANSWER APPROPRIATELLY
            this.colorTheAnswer(this.answerStatus);
            // WAIT FOR 3 SECONDS
            // FURTHER HANDLE ANSWER RESPONSE
            setTimeout(this.handleAnswerResponseFurther,3000);

        },

        handleAnswerResponseFurther(){
            // ANSWER IS CORRECT
            if(this.answerStatus){
                // GET NEW QUESTION
                this.setNewQuestion();
            }
            // ANSWER IS INCORRECT
            else{
                // RUN showModal BUS METHOD ON Modal
                EventBus.$emit('showModal',this.showGameModal('Your answer was incorrect!'));
            }
        },


        stopTheTimer(){
            // CALL FOR stopTheTimer METHOD IN GameTime
            EventBus.$emit('stopTheTimer');
        },

        colorTheAnswer(status){
            // CALL FOR colorTheAnswer METHOD IN GameAnswers
            EventBus.$emit('colorTheAnswer', {
                status,
            });
        },

        showGameModal(title){
            // SET NEW MODAL TITLE
            this.modalInformations.title = title;

            // SET MODAL html
            this.modalInformations.html = 'Playing as: <strong>'+this.username+'</strong><br>Your score: <strong>'+this.score+'</strong>';

            // RUN showModal BUS METHOD ON Modal
            EventBus.$emit('showModal',this.modalInformations);

            // RESET MODAL TITLE
            this.modalInformations.title = 'Your answer was incorrect!';
        },

        goBack(){
            // GO TO MENU
            this.$router.push('/menu');
        }
    },

    created(){
        // GET QUESTION
        setTimeout(()=>{
            this.getQuestion();
        }, 2000);

        // BUS METHODS
        EventBus.$on('startNewGame', this.startNewGame);
        EventBus.$on('goBack', this.goBack);
        EventBus.$on('answered', answer => {
            // USER'S ANSWER
            this.answer = answer;
            // HANDLE USER'S ANSWER
            this.answered();
        });
        EventBus.$on('showGameModal',data => {
            // ADD ADDITIONAL MODAL INFORMATIONS
            this.showGameModal(data.title);
        });
    },


}
</script>

<style>

</style>
