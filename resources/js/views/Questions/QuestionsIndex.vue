<template>
    <div class="container visible question-container-outer">
        <div class="text-center pb-5">
            <img src="/img/logo.png" alt="logo" class="logo-height">
        </div>
        <div class="d-flex justify-content-center my-3">
            <button class="btn btn-main text-center" @click="goQuestionCreate()">New Question</button>
        </div>
        <div class="d-flex justify-content-center mb-3">
            <div v-for="difficulty in difficulties" :key="difficulty.id" class="mx-3 btn btn-main text-center" :class="{'btn-main-active': difficulty.id === pagination.difficulty}" @click="changeDifficulty(difficulty.id)">{{ difficulty.text }}</div>
        </div>

        <div class="p-3 question-container-inner">
            <!-- HIDE QUESTIONS CONTATINER IF THERE ARE NO QUESTIONS -->
            <div v-if="questions.length !== 0 ">
                <div v-for="(question, index) in questions" :key="index" class="animated slideInDown fast text-white question-wrapper my-3">
                    <div class="d-flex" v-if="questionEditId !== question.id">
                        <p class="lead px-2 my-auto flex-grow-1">{{ question.text }}</p>
                        <div class="question-icon">
                            <i class="fas fa-angle-down" @click="questionEditId = question.id"></i>
                        </div>
                    </div>
                    <div v-else>
                        <QuestionsEdit :question="question" :difficulties="difficulties" @closeEdit="closeEdit" @reloadQuestions="reloadQuestions"></QuestionsEdit>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <button class="btn btn-main text-center w-auto" @click="loadMore()" :disabled="!isNotOnLastPage">{{ loadButtonText }}</button>
                </div>
            </div>
        </div>

        <div class="text-white text-center">
            <i class="fas fa-long-arrow-alt-left back" @click="goBack" alt="back"></i>
        </div>
    </div>
</template>

<script>

import QuestionsEdit from './QuestionsEdit';

export default {
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

        goBack(){
            // GO BACK TO MENU
            this.$router.push('/menu');
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
            // FETCH QUESTIONS AGAIN
            this.getQuestions();
            // RESET EDIT VIEW
            this.closeEdit();
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
    },
    components: {
        // COMPONENT FOR QUESTION EDITING
        QuestionsEdit
    }
}
</script>
