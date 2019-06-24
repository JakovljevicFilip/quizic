<template>
    <div class="h-100 d-flex flex-column">
        <!-- GAME SCREEN -->
        <template v-if="questionRecieved">
            <GameLogo></GameLogo>
            <GameHints :game_id="game_id"></GameHints>
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
            // GAME ID USED WITH SESSION
            game_id: '',
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
            this.game_id = game.game_id;
            this.username = game.username;
            this.score = game.score;
            this.question = game.question;
        },

        getQuestion(){
            // RUN GET QUESTION REQUEST
            this.$http.get('game/getQuestion')
            .then(response => {
                console.log(response);
            })
            .catch(error => {});
        },

        startNewGame(){
            // RESET INFORMATIONS
            this.game_id = '';
            this.question = {};
            this.answer = {};
            this.answerStatus = null;
            this.username = '';
            this.score = 0;
            this.newQuestion = {};
            this.correctAnswer = null;

            // RESTART THE GAME
            this.startGame();
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
                        game_id: this.game_id,
                        id: this.answer.id,
                    }
                }
            })
            .then(response => {
                let message = response.body.message;
                let game = response.body.game;

                if(message === 'Answer is incorrect.'){
                    this.answerStatus = false;
                    this.correctAnswer = game.correct_answer;
                }
                else{
                    this.answerStatus = true;
                    this.newQuestion = game.question;
                    this.score = game.score;
                    this.correctAnswer = this.answer.id;
                }
                this.handleAnswerResponse();
            })
            .catch(error => {});
        },

        handleAnswerResponse(){
            // COLOR THE ANSWER APPROPRIATELLY
            this.colorTheAnswer(this.answerStatus, this.correctAnswer);
            // WAIT FOR 3 SECONDS
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
                EventBus.$emit('showModal',this.showGameModal('Your answer was incorrect!'));
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
        },

        endGame(){
            // RUN END GAME REQUEST
            this.$http.delete('game/destroy', {
                params: {
                    game_id: this.game_id,
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
    },

    beforeDestroy(){
        EventBus.$off('answered');
        // REMOVE GAME FROM DB
        this.endGame();
    },
}
</script>

<style>

</style>
