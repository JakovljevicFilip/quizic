<template>
    <div>
        <div v-for="question in questions" :key="question.id">
            {{ question.text}}
            <ul v-for="answer in question.answers" :key="answer.id">
                <li>{{ answer.text }}</li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return {
            questions: [],
        }
    },
    methods:{
        getQuestions(){
            this.$http.get('questions')
            .then(response =>{
                this.questions = response.body.questions;
            })
            .catch(error => {
                console.log(error);
            });
        }
    },
    created(){
        this.getQuestions();
    }
}
</script>

<style lang="scss">
    @import "../../../sass/application/question.scss";
</style>
