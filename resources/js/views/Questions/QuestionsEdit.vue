<template>
    <div class="p-2 question-grid-edit">
        <div class="question__text">
            <label for="questionText" class="lead text-white">Question text:</label>
            <input id="questionText" name="text" class="lead px-1 form-control w-100 input" v-validate="rules.text" v-model="questionClone.text">
        </div>

        <div class="question__difficulties">
            <label for="difficulty" class="lead text-white">Question difficulty:</label>
            <select id="difficulty" name="difficulty" class="form-control input" v-model="questionClone.difficulty_id" v-validate="rules.difficulty">
                <option v-for="difficulty in difficulties" :key="difficulty.id" :value="difficulty.id" :selected="questionClone.difficulty_id === difficulty.id">{{ difficulty.text }}</option>
            </select>
        </div>

        <div class="icon question__collapse">
            <i class="fas fa-angle-up" @click="closeEdit()"></i>
        </div>

        <div class="question__answers">
            <p class="lead text-white ml-1 mb-0">Question answers:</p>
            <div class="answer-grid mb-3 p-3">
                <div v-for="answer in questionClone.answers" :key="answer.id" class="text-center answer" :class="{'answer--correct' : answer.status, 'answer--incorrect': !answer.status}" @click.self="answerStatusChange(answer)">
                    <input type="text" v-validate="rules.answer" name="answer" v-model="answer.text" class="text-center text-white answer__input">
                </div>
            </div>
        </div>


        <div class="text-center pb-3 question__buttons">
            <i class="fas fa-check icon icon__confirm mr-3" @click="validate"></i>
            <QuestionsDelete :question="question"></QuestionsDelete>
        </div>
    </div>
</template>

<script>
import QuestionsDelete from './QuestionsDelete';
import {EventBus} from '../../app';

export default {
    props: ['question', 'difficulties'],
    components: {
        // DELETE FUNCTIONALITY
        QuestionsDelete,
    },
    data(){
        return{
            // KEEP DATA SEPARATE FROM ORIGINAL OBJECT
            questionClone: {},
            // VEE VALIDATE
            rules: {
                text: 'required',
                answer: 'required',
                difficulty: 'required|numeric'
            },
        }
    },
    methods: {
        setQuestionClone(){
            // KEEP ORIGINAL QUESTION DATA AND EDIT NEW OBJECT INSTEAD
            this.questionClone = JSON.parse(JSON.stringify(this.question));
        },

        answerStatusChange(answer){
            // SET NEW ANSWER STATUS
            answer.status = 1;
            // CLEAR ALL OTHER ANSWERS STATUSES
            this.answerClearStatuses(answer.id);
        },

        answerClearStatuses(avoid){
            // CHANGED ANSWERS
            let answers = this.questionClone.answers;
            // ITTERATE THROUGH ANSWERS
            for(let i=0; i < answers.length; i++){
                // ANSWER IS NOT THE RECENTLY CHANGED ONE
                if(answers[i].id !== avoid)
                    // SET TO INCORRECT ANSWER
                    answers[i].status = 0;
            }
        },

        validate(){
            this.$validator.validate()
            .then(valid => {
                // VALIDATION PASSED
                if (valid) {
                    // RUN AJAX REQUEST
                    this.questionUpdate();
                }
                // VALIDATION DIDN'T PASS
                else{
                    // SHOW ALERT
                    let message = this.$validator.errors.all()[0];
                    this.$swal('Question', message, 'error');
                }
            });
        },

        questionUpdate(){
            this.$http.put('questions', this.questionClone)
            .then(response => {
                // RELOAD QUESTIONS ARRAY ON PARENT COMPONENT
                EventBus.$emit('reloadQuestions');
            })
            .catch(error => {});
        },

        closeEdit(){
            // RESETS EDIT VIEW
            EventBus.$emit('closeEdit');
        },
    },
    created(){
        // CLONE QUESTION SO THAT ORIGINAL WOULDN'T BE CHANGED
        this.setQuestionClone();
    }
}
</script>
