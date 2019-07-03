<template>
    <div class="h-100 d-flex flex-column">
        <!-- GAME SCREEN -->
        <template v-if="questionRecieved">
            <GameLogo></GameLogo>
            <GameHints :hash="hash"></GameHints>
            <GameInfo :username="username" :score="score"></GameInfo>
            <GameTime :score="score"></GameTime>
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
        // SOLVE, SWITCH, HALF
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
            // GAME IDENTIFIER
            hash: '',
            // QUESTION OBJECT
            question: {},
            // USERS ANSWER TO A QUESTION
            answer: {},
            // ANSWER IS CORRECT/INCORRECT
            answerStatus: null,
            // PLAYING AS
            username: '',
            // CORRECT QUESTIONS SO FAR
            score: 0,
            // QUESTION THAT IS YET TO BE SHOWN
            newQuestion: {},
            // CORRECT ANSWER
            correctAnswer: null,
            // MODAL
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
            // API MESSAGE
            apiMessage: '',
        }
    },

    methods:{
        startGame(){
            // RUN START GAME REQUEST
            this.$http.get('game/startGame')
            .then(response => {
                let game = response.body.game;
                this.handleNewQuestion(game);
            })
            .catch(error => {});
        },

        handleNewQuestion(game){
            this.hash = game.hash;
            this.username = game.username;
            this.score = game.score;
            this.question = game.question;
        },

        startNewGame(){
            // RESET INFORMATIONS
            this.resetGameInformations();
            // RESTART THE GAME
            this.startGame();
        },

        resetGameInformations(){
            // SET DEFAULT VALUES
            this.hash = '';
            this.question = {};
            this.answer = {};
            this.answerStatus = null;
            this.username = '';
            this.score = 0;
            this.newQuestion = {};
            this.correctAnswer = null;
        },

        answered(){
            // STOPS TIMER
            this.stopTheTimer();
            // SUBMITS ANSWER
            this.submitAnswer();
        },

        submitAnswer(){
            this.$http.get('game/answer',{
                params: {
                    answer: {
                        hash: this.hash,
                        id: this.answer.id,
                    }
                }
            })
            .then(response => {
                // SET REFERENCE TO THE API MESSAGE
                this.answerStatus = response.body.status;
                // GET API MESSAGE
                this.apiMessage = response.body.message;

                // SET REFRENCE TO GAME
                let game = response.body.game;
                // ANSWER IS CORRECT
                if(this.answerStatus){
                    // CHANGE QUESTION
                    this.newQuestion = game.question;
                    // CHANGE SCORE
                    this.score = game.score;
                    // SET REFERENCE TO THE CORRECT ANSWER
                    this.correctAnswer = this.answer.id;
                }
                // ANSWER IS INCORRECT
                else{
                    // SET REFERENCE TO THE CORRECT ANSWER
                    this.correctAnswer = game.correct_answer;
                }
                // HANDLE RESPONSE
                this.handleAnswerResponse();
            })
            .catch(error => {});
        },

        handleAnswerResponse(){
            // COLOR THE ANSWER APPROPRIATELLY
            this.colorTheAnswer(this.answerStatus, this.correctAnswer);
            // WAIT FOR 3 SECONDS, FOR THE ANIMATIONS TO RUN
            // FURTHER HANDLE ANSWER RESPONSE
            setTimeout(this.handleAnswerResponseFurther,3000);
        },

        handleAnswerResponseFurther(){
            // ANSWER IS CORRECT
            if(this.answerStatus){
                // SET NEW QUESTION
                this.question = this.newQuestion;
                // RUN resetTheTimer BUS METHOD ON Timer
                this.resetTheTimer();
            }
            // ANSWER IS INCORRECT
            else{
                // RUN showModal BUS METHOD ON Modal
                EventBus.$emit('showModal',this.showGameModal());
            }
        },


        stopTheTimer(){
            // CALL FOR stopTheTimer METHOD IN GameTime
            EventBus.$emit('stopTheTimer');
        },

        resetTheTimer(){
            // RUN resetTheTimer BUS METHOD ON Timer
            EventBus.$emit('resetTheTimer',this.resetTheTimer);
        },

        colorTheAnswer(status, correctAnswer){
            // CALL FOR colorTheAnswer METHOD IN GameAnswers
            EventBus.$emit('colorTheAnswer', {
                status,
                correctAnswer,
            });
        },

        showGameModal(title){
            // SET NEW MODAL TITLE
            this.modalInformations.title = this.apiMessage;

            // SET MODAL html
            this.modalInformations.html = 'Playing as: <strong>'+this.username+'</strong><br>Your score: <strong>'+this.score+'</strong>';

            // RUN showModal BUS METHOD ON Modal
            EventBus.$emit('showModal',this.modalInformations);

            // RESET MODAL TITLE
            this.modalInformations.title = '';
        },

        goBack(){
            // GO TO MENU
            this.$router.push('/menu');
        },

        endGame(){
            // RUN END GAME REQUEST
            this.$http.delete('game/destroy', {
                params: {
                    hash: this.hash,
                }
            })
            .catch(error => {});
        },
    },

    created(){
        // START GAME
        this.startGame();

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
        EventBus.$on('hintSwitch', data => {
            // STOP THE TIMER
            this.stopTheTimer();
            // HANDLE AS CORRECT ANSWER
            this.answerStatus = true;
            // SET NEW QUESTION REFERENCE
            this.newQuestion = data.question;
            // CONTINUE GAME
            this.handleAnswerResponseFurther();
        });
    },

    beforeDestroy(){
        EventBus.$off('answered');
        EventBus.$off('startNewGame');
        // REMOVE GAME FROM DB
        this.endGame();
    },
}
</script>

<style>

</style>
