<template>
<div class="h-100">
    <!-- HIDE QUESTIONS CONTATINER IF THERE ARE NO QUESTIONS -->
    <div class="d-flex flex-column p-3 container-index__scroll"  v-if="questions.length !== 0 ">
        <div class="d-flex justify-content-center my-3">
            <button class="btn text-center btn-main" @click="goQuestionCreate()">New Question</button>
        </div>
        <div class="d-flex justify-content-center mb-3">
            <div v-for="difficulty in difficulties" :key="difficulty.id" class="mx-3 text-center btn btn-main" :class="{'btn-main--active': difficulty.id === pagination.difficulty}" @click="changeDifficulty(difficulty.id)">{{ difficulty.text }}</div>
        </div>

        <div class="flex-grow-1">
            <div class="container-index__questions">
                <div v-for="(question, index) in questions" :key="index" class="animated slideInDown text-white  my-3 wrapper">
                    <div class="p-2 question-grid-show" v-if="questionEditId !== question.id">
                        <p class="lead my-auto question__text">{{ question.text }}</p>
                        <div class="icon question__collapse">
                            <i class="fas fa-angle-down" @click="questionEditId = question.id"></i>
                        </div>
                    </div>
                    <QuestionsEdit :question="question" :difficulties="difficulties" v-else></QuestionsEdit>
                </div>
                <div class="d-flex justify-content-center">
                    <button class="btn text-center w-auto btn-main" @click="loadMore()" :disabled="!isNotOnLastPage">{{ loadButtonText }}</button>
                </div>
            </div>
        </div>
    </div>

    <Loading v-else></Loading>
</div>

</template>

<script>
// COMPONENTS
import QuestionsEdit from './QuestionsEdit';
import Loading from '../Loading';

// EVENT BUS
import {EventBus} from '../../app';

export default {
    components: {
        // COMPONENT FOR QUESTION EDITING
        QuestionsEdit,
        // LOADING SCREEN
        Loading,
    },
    data(){
        return {
            // DATA USED FOR FETCHING QUESTIONS
            pagination: {
                // QUESTIONS DIFFICULTY
                difficulty: 1,
                // CURRENT PAGE FOR PAGINATION
                page: 1,
                // NUMBER OF QUESTIONS BEING FETCHED PER REQUEST
                per_page: 15,
                // LAST PAGE FOR PAGIANTION
                last_page: null,
            },
            // ARRAY USED FOR SHOWING QUESTIONS
            questions: [],
            // DIFFICULTY NAVIGATION BUTTONS
            difficulties: [],
            // ID OF QUESTION THAT IS BEING EDITED
            questionEditId: null,
        }
    },
    computed: {
        isNotOnLastPage: function(){
            return this.pagination.page <= this.pagination.last_page;
        },
        loadButtonText: function(){
            return this.pagination.page <= this.pagination.last_page ? 'Load more questions' : 'No more questions';
        },
    },
    methods:{
        getDifficulties(){
            this.$http.get('difficulties')
            .then(response =>{
                // SETS DIFFICULTY BUTTONS
                this.difficulties = response.body.difficulties;
            })
            // CATCHES ERROR IF THERE IS ONE
            .catch(error => {});
        },

        getQuestions(append){
            this.$http.get('questions',{
                params: {
                    'difficulty': this.pagination.difficulty,
                    'page': this.pagination.page,
                    'per_page': this.pagination.per_page,
                }
            })
            .then(response =>{
                // SHOULD APPEND TO QUESTIONS
                if(append){
                    // APPEND NEW QUESTIONS TO QUESTIONS ARRAY
                    this.questions = this.questions.concat(response.body.questions);
                }
                // SHOULD RESET QUESTIONS ARRAY
                else{
                    this.questions = [];
                    // RESET QUESTIONS ARRAY
                    this.questions = response.body.questions;
                }
                // SET LAST PAGE
                this.pagination.last_page = response.body.last_page;
                // INCREMENT CURRENT PAGE
                this.pagination.page++;
            })

            .catch(error => {});
        },

        loadMore(){
            // ARE THERE MORE QUESTIONS THAT COULD BE FETCHED
            if(this.isNotOnLastPage){
                // FETCH QUESTIONS
                this.getQuestions(true);
            }
        },

        changeDifficulty(difficulty){
            // SET NEW DIFFICULTY
            this.pagination.difficulty = difficulty;
            // RESET CURRENT PAGE PROPERTY USED FOR PAGIANTION
            this.pagination.page = 1;
            // FETCH QUESTIONS
            this.getQuestions();
        },

        goQuestionCreate(){
            // GO TO QUESTION CREATE PAGE
            this.$router.push('questions/create');
        },

        reloadQuestions(){
            // RESET PAGINATION PAGE PROPERTY
            this.pagination.page = 1;
            // CLOSE EDIT VIEW
            this.closeEdit();
            // FETCH QUESTIONS AGAIN
            this.getQuestions();
        },

        closeEdit(){
            // RESET QUESTION THAT IS BEING EDITED
            this.questionEditId = null;
        }
    },
    created(){
        // GET DIFFICULTIES
        this.getDifficulties();
        // GET QUESTIONS
        this.getQuestions();

        // BUS METHODS
        EventBus.$on('reloadQuestions', this.reloadQuestions);
        EventBus.$on('closeEdit', this.closeEdit);
    },

    beforeDestroy () {
        // NECESSARY SINCE COMPONENT IS BEING RELOADED ON CHANGE
        EventBus.$off('reloadQuestions', this.reloadQuestions)
    },
}
</script>
