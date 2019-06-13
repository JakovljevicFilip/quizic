<template>
<div class="h-100">
     <div class="h-100 p-3 d-flex flex-column" v-if="this.question.text !== undefined">
        <div class="text-center my-3">
            <img src="/img/logo.png" alt="logo" class="logo--height">
        </div>

        <div class="animated slideInDown fast flex-grow-1 container h-100 d-flex justify-content-center align-items-center p-1">
            <div class="wrapper wrapper--lg">
                <div class="d-flex mb-3">
                    <div class="d-inline-block flex-grow-1 mr-1">
                        <label for="questionText" class="lead text-white">Question text:</label>
                        <input id="questionText" name="text" class="lead px-1 form-control w-100 input" v-validate="rules.text" v-model="question.text">
                    </div>

                    <div class="d-inline-block">
                        <label for="difficulty" class="lead text-white">Question difficulty:</label>
                        <select id="difficulty" name="difficulty" class="form-control input" v-model="question.difficulty_id" v-validate="rules.difficulty">
                            <option v-for="difficulty in difficulties" :key="difficulty.id" :value="difficulty.id" :selected="question.difficulty_id === difficulty.id">{{ difficulty.text }}</option>
                        </select>
                    </div>
                </div>

                <p class="lead text-white ml-1 mb-0">Question answers:</p>
                <div class="answer-grid">
                    <div v-for="answer in question.answers" :key="answer.id" class="text-center answer" :class="{'answer--correct' : answer.status, 'answer--incorrect': !answer.status}">
                        <input type="text" v-validate="rules.answer" name="answer" v-model="answer.text" class="text-center text-white answer__input">
                    </div>
                </div>

                <div class="text-center my-3">
                    <i class="fas fa-check question-icon icon icon__confirm" @click="validate"></i>
                </div>
            </div>
        </div>
    </div>

    <Loading v-else></Loading>
</div>

</template>

<script>
import Loading from '../Loading';

export default {
    components: {
        // LOADING SCREEN
        Loading,
    },
    data(){
        return{
            question: {},
            difficulties: [],
            // VEE VALIDATION
            rules: {
                text: 'required',
                difficulty: 'required|numeric',
                answer: 'required'
            },
        }
    },
    methods: {
        getDifficulties(){
            this.$http.get('difficulties')
            .then(response => {
                // SET DIFFICULTIES
                this.difficulties = response.body.difficulties;
                // SET DEFAULT VALUES FOR QUESTION FIELDS
                this.setDefaultQuestionValues();
            })
            .catch(error => {})
        },

        setDefaultDifficulty(){
            // SET SELECTED QUESTION DIFFICULTY TO THE FIRST ARRAY MEMBER
            this.question.difficulty_id = this.difficulties[0].id;
        },

        validate(){
            this.$validator.validate()
            .then(valid => {
                // VALIDATION PASSED
                if (valid) {
                    // RUN AJAX REQUEST
                    this.questionStore();
                }
                // VALIDATION DIDN'T PASS
                else{
                    // SHOW ERROR MESSAGES BENEATH INPUT FIELDS
                    this.$validator.validateAll();
                    // SHOW ALERT
                    let message = this.$validator.errors.all()[0];
                    this.$swal('Question', message, 'error');
                }
            });
        },

        questionStore(){
            this.$http.post('questions', this.question)
            .then(repsonse => {
                // RESET QUESTION FIELDS
                this.setDefaultQuestionValues();
            })
            .catch(error => {
                console.log(error);
            });
        },

        setDefaultQuestionValues(){
            // DEFAULT QUESTION FIELDS
            this.question = {
                text: 'Enter text here.',
                difficulty_id: this.difficulties[0].id,
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
    },
    mounted(){
        // FETCH DIFFICULTIES
        this.getDifficulties();
    }
}
</script>
