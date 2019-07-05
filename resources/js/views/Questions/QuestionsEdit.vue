<template>
    <div class="p-2 question-grid-edit">
        <!-- QUESTION TEXT -->
        <div class="question__text">
            <label for="questionText" class="lead text-white">Question text:</label>
            <input id="questionText" name="text" class="lead px-1 form-control w-100 input" v-validate="rules.text" v-model="questionClone.text">
        </div>

        <!-- QUESTION DIFFICULTIES -->
        <div class="question__difficulties">
            <label for="difficulty" class="lead text-white">Question difficulty:</label>
            <select id="difficulty" name="difficulty" class="form-control input" v-model="questionClone.difficulty_id" v-validate="rules.difficulty">
                <option v-for="difficulty in difficulties" :key="difficulty.id" :value="difficulty.id" :selected="questionClone.difficulty_id === difficulty.id">{{ difficulty.text }}</option>
            </select>
        </div>

        <!-- EDIT VIEW TOGGLE -->
        <div class="icon question__collapse">
            <i class="fas fa-angle-up" @click="closeEdit()" v-tooltip.bottom="'Close edit view'"></i>
        </div>
        <!-- QUESTION ANSWERS -->
        <div class="question__answers">
            <p class="lead text-white ml-1 mb-0">Question answers:</p>
            <!-- QUESTION ANSWER -->
            <div class="answer-grid mb-3 p-3">
                <div v-for="answer in questionClone.answers" :key="answer.id" class="text-center answer" :class="{'answer--correct' : answer.status, 'answer--incorrect': !answer.status}" @click.self="answerStatusChange(answer)" v-tooltip.bottom="answer.status ? 'Correct answer' : 'Incorrect answer'">
                    <input type="text" v-validate="rules.answer" name="answer" v-model="answer.text" class="text-center text-white answer__input">
                </div>
            </div>
        </div>

        <!-- SAVE CHANGED OR DELETE -->
        <div class="text-center pb-3 question__buttons">
            <!-- VALIDATE FIELDS BEFORE UPDATE -->
            <span @click="validateQuestion">
                <!-- QUESTION UPDATE -->
                <QuestionsUpdate :question="questionClone"></QuestionsUpdate>
            </span >
            <!-- QUESTION DELETE -->
            <QuestionsDelete :question="question"></QuestionsDelete>
        </div>
    </div>
</template>

<script>
// COMPONENTS
import QuestionsDelete from './QuestionsDelete';
import QuestionsUpdate from './QuestionsUpdate';

// EVENT BUS
import {EventBus} from '../../app';

export default {
    // PASSED FROM QuestionsIndex
    props: ['question', 'difficulties'],
    components: {
        // DELETE FUNCTIONALITY
        QuestionsDelete,
        // UPDATE FUNCTIONALITY
        QuestionsUpdate,
    },
    data(){
        return{
            // KEEP DATA SEPARATE FROM ORIGINAL OBJECT
            // IF EDIT IS CANCELED ORIGINAL OBJECT IS PRESERVED
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
            // CONVERT TO STRING AND BACK TO OBJECT
            this.questionClone = JSON.parse(JSON.stringify(this.question));
        },

        answerStatusChange(answer){
            // SET NEW ANSWER STATUS
            answer.status = 1;
            // CLEAR ALL OTHER ANSWERS STATUSES
            this.answerSetToIncorrect(answer.id);
        },

        answerSetToIncorrect(avoid){
            // ANSWERS
            let answers = this.questionClone.answers;
            // ITTERATE THROUGH ANSWERS
            for(let i=0; i < answers.length; i++){
                // ANSWER IS NOT THE RECENTLY CHANGED ONE
                if(answers[i].id !== avoid)
                    // SET TO INCORRECT ANSWER
                    answers[i].status = 0;
            }
        },

        validateQuestion(){
            this.$validator.validate()
            .then(valid => {
                // VALIDATION PASSED
                if (valid) {
                    // RUN questionsUpdate BUS METHOD ON QuestionsIndex
                    EventBus.$emit('questionUpdate');
                }
                // VALIDATION DIDN'T PASS
                else{
                    // SHOW ALERT
                    let message = this.$validator.errors.all()[0];
                    this.$swal('Question', message, 'error');
                }
            });
        },

        closeEdit(){
            // RUN closeEdit BUS METHOD ON QuestionsIndex
            EventBus.$emit('closeEdit');
        },
    },
    created(){
        // CLONE QUESTION SO THAT ORIGINAL WOULDN'T BE CHANGED
        this.setQuestionClone();
    }
}
</script>
