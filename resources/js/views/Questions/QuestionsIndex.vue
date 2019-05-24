<template>
    <div class="container visible question-container-outer">
        <div class="text-center pb-5">
            <img src="/img/logo.png" alt="logo" class="logo-height">
        </div>
        <div class="mb-5 d-flex justify-content-center">
            <div v-for="difficulty in difficulties" :key="difficulty.id" class="mx-3 btn btn-main text-center" @click="changeDifficulty(difficulty.id)">{{ difficulty.text }}</div>
        </div>
        <div class="d-flex justify-content-center mb-3 question-navigation">
                <button class="btn btn-main text-center w-auto" @click="loadMore()" :disabled="!isNotOnLastPage">{{ loadButtonText }}</button>
                <button class="btn btn-main text-center" @click="goQuestionCreate()">New Question</button>
            </div>
        <div class="question-container-inner p-3">
            <div v-for="(question, index) in questions" :key="index" class="text-white question-wrapper px-3 my-3">
                <div class="question-wrapper d-flex align-items-center" v-if="questionOriginal.id !== question.id">
                    <p class="lead px-1 flex-grow-1">{{ question.text }}</p>
                    <div class="question-icon">
                        <i class="fas fa-angle-down" @click="setQuestionReferences(question)"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-white text-center">
            <i class="fas fa-long-arrow-alt-left back" @click="goBack" alt="back"></i>
        </div>
    </div>
</template>

<script>

export default {
    data(){
        return {
            // DATA USED FOR FETCHING QUESTIONS
            fetchQuestions: {
                // QUESTIONS DIFFICULTY
                difficulty: 1,
                // CURRENT PAGE FOR PAGINATION
                page: 1,
                // NUMBER OF QUESTIONS BEING FETCHED PER REQUEST
                per_page: 6,
                // LAST PAGE FOR PAGIANTION
                last_page: null,
            },
            questions: [],
            difficulties: [],
            // QUESTION THAT IS BEING VIEWED
            questionOriginal: {},
            // QUESTION OBJECT THAT IS BEING CHANGED
            questionChanged: {},
            // IS AN ANSWER CHANGED
            answerIsChanged: false,
            // IS QUESTION DIFFICULTY CHANGED
            difficultyIsChanged: false,
            // IS QUESTIONS TEXT CHANGED
            textIsChanged: false,
            // CSS EXPANDED DOWN
            expandedDown: 'question-icon fas fa-angle-up',
            // CSS EXPANDED UP
            expandedUp: 'question-icon fas fa-angle-down',
        }
    },
    computed: {
        isNotOnLastPage: function(){
            return this.fetchQuestions.page <= this.fetchQuestions.last_page;
        },
        loadButtonText: function(){
            return this.fetchQuestions.page <= this.fetchQuestions.last_page ? 'Load more questions' : 'No more questions';
        },
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
            this.$http.get('questions',{
                params: {
                    'difficulty': this.fetchQuestions.difficulty,
                    'page': this.fetchQuestions.page,
                    'per_page': this.fetchQuestions.per_page,
                }
            })
            .then(response =>{
                // ADD QUESTIONS TO DISPLAY ARRAY
                this.questions = this.questions.concat(response.body.questions);
                // SET LAST PAGE
                this.fetchQuestions.last_page = response.body.last_page;
                // INCREMENT CURRENT PAGE
                this.fetchQuestions.page++;
            })
            .catch(error => {
                console.log(error);
            });
        },

        goBack(){
            this.$router.push('/menu');
        },

        loadMore(){
            if(this.isNotOnLastPage){
                this.getQuestions();
            }
        },

        changeDifficulty(difficulty){
            // EMPTY QUESTIONS ARRAY
            this.questions = [];
            // SET NEW DIFFICULTY
            this.fetchQuestions.difficulty = difficulty;
            // RESET PAGE PROPERTY
            this.fetchQuestions.page = 1;
            // RESET SHOWING QUESTION
            this.resetViewQuestion();
            this.getQuestions();
        },

        setViewQuestion(question){
            // RESET COMPARISON CHECK
            this.questionIsChanged = false;
            // QUESTION IS NOT ALREADY SHOWING
            if(this.questionOriginal.id != question.id){
                // SETUP QUESTION COMPARISON
                this.setQuestionReferences(question);
            }
            else {
                // RESET SHOWING QUESTION
                this.resetViewQuestion();
            }
        },

        resetViewQuestion(){
            this.questionOriginal = {};
            this.questionChanged = {};
            this.answerIsChanged = false;
            this.difficultyIsChanged = false;
            this.textIsChanged = false;
        },

        // COMPARES QUESTION DURING EDIT
        setQuestionReferences(question){
            // MAKES NEW OBJECT OUT OF ORIGINAL QUESTION IN ORDER TO COMPARE THE TWO VERSIONS
            this.questionOriginal = JSON.parse(JSON.stringify(question));
            this.questionChanged = JSON.parse(JSON.stringify(question));
        },

        textChange(){
            this.textIsChanged = this.questionOriginal.text !== this.questionChanged.text;
            console.log(this.questionOriginal.text !== this.questionChanged.text);
        },

        answerChange(){
            let answersOriginal = this.questionOriginal.answers;
            let answersChanged = this.questionChanged.answers;
            // ITTERATE THROUGH ALL ANSWERS
            for(let i=0; i < answersOriginal.length; i++){
                // ITTERATE THROUGH ALL PROPERTIES
                for(let key in answersOriginal[i]){
                    // THERE IS A DIFFERENCE IN PROPERTY
                    if(answersOriginal[i][key] !== answersChanged[i][key]){
                        console.log('entered');
                        // ANSWER IS CHANGED
                        return this.answerIsChanged = true;
                    }
                }
            }
            // NO ANSWER IS CHANGED
            return this.answerIsChanged = false;
        },

        answerStatusChange(answer){
            // SET NEW ANSWER STATUS
            answer.status = 1;
            // CLEAR ALL OTHER ANSWERS STATUSES
            this.answerClearStatuses(answer.id);
        },

        answerClearStatuses(avoid){
            // CHANGED ANSWERS
            let answers = this.questionChanged.answers;
            // ITTERATE THROUGH ANSWERS
            for(let i=0; i < answers.length; i++){
                // ANSWER IS NOT RECENTLY CHANGED ONE
                if(answers[i].id !== avoid)
                    // SET TO INCORRECT ANSWER
                    answers[i].status = 0;
            }
            // RE-CHECK ANSWERS
            this.answerChange();
        },

        difficultyChange(){
            this.difficultyIsChanged = this.questionOriginal.difficulty_id !== this.questionChanged.difficulty_id;
        },
        goQuestionCreate(){
            this.$router.push('questions/create');
        },
    },
    created(){
        this.getDifficulties();
        this.getQuestions();
    }
}
</script>

<style lang="scss" scoped>
    @import "../../../sass/application/question.scss";
</style>
