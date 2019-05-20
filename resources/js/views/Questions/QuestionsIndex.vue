<template>
    <div class="container visible question-container">
        <div class="text-center pb-5">
            <img src="/img/logo.png" alt="logo" class="logo-height">
        </div>
        <div class="mb-5 d-flex justify-content-center">
            <div v-for="difficulty in difficulties" :key="difficulty.id" class="mx-3 btn btn-main text-center">{{ difficulty.text }}</div>
        </div>
        <div class="question-wrapper">
            <div v-for="question in questions" :key="question.id" class="row mt-2">
                <div class="col-11 text-white">
                    <div class="lead question-field">
                        <p>{{ question.text }}</p>
                    </div>
                    <ul class="d-none" v-for="answer in question.answers" :key="answer.id">
                        <li>{{ answer.text }}</li>
                    </ul>
                </div>
                <div class="col-1 my-auto">
                    <i class="fas fa-times question-delete"></i>
                </div>
            </div>
        </div>
        <div class="back text-white text-center">
            <i class="fas fa-long-arrow-alt-left" @click="goBack" alt="back"></i>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return {
            questions: [],
            difficulties: [],
        }
    },
    methods:{
        getDifficulties(){
            this.$http.get('difficulties')
            .then(response =>{
                this.difficulties = response.body.difficulties;
            })
            .catch(error => {
                console.log(error);
            });
        },

        getQuestions(){
            this.$http.get('questions')
            .then(response =>{
                this.questions = response.body.questions;
            })
            .catch(error => {
                console.log(error);
            });
        },

        goBack(){
            this.$router.push('/menu');
        }
    },
    created(){
        this.getDifficulties();
        this.getQuestions();

    }
}
</script>

<style lang="scss">
    @import "../../../sass/application/question.scss";
</style>
