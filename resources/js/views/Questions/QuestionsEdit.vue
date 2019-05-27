<template>
    <div>
        <div class="d-flex">
            <div class="d-inline-block flex-grow-1 mr-1 p-1">
                <label for="questionText" class="lead text-white">Question text:</label>
                <input id="questionText" name="text" class="lead px-1 form-control w-100 input" v-validate="rules.text" v-model="questionClone.text">
            </div>

            <div class="d-inline-block p-1 question__select">
                <label for="difficulty" class="lead text-white">Question difficulty:</label>
                <select id="difficulty" name="difficulty" class="form-control input" v-model="questionClone.difficulty_id" v-validate="rules.difficulty">
                    <option v-for="difficulty in difficulties" :key="difficulty.id" :value="difficulty.id" :selected="questionClone.difficulty_id === difficulty.id">{{ difficulty.text }}</option>
                </select>
            </div>
            <div class="icon">
                <i class="fas fa-angle-up" @click="closeEdit()"></i>
            </div>
        </div>

        <p class="lead text-white ml-1 mb-0">Question answers:</p>
        <div class="answer-grid mb-3 p-3">
            <div v-for="answer in questionClone.answers" :key="answer.id" class="text-center answer" :class="{'answer--correct' : answer.status, 'answer--incorrect': !answer.status}" @click.self="answerStatusChange(answer)">
                <input type="text" v-validate="rules.answer" name="answer" v-model="answer.text" class="text-center text-white answer__input">
            </div>
        </div>

        <div class="text-center pb-3">
            <i class="fas fa-check icon icon--confirm" @click="validate"></i>
            <i class="fas fa-times icon icon--times" @click="deleteController"></i>
        </div>
    </div>
</template>

<script>
export default {
    props: ['question', 'difficulties'],
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
            // DELETE MODAL CONFIGURATION
            swalDeleteConfig: {
                // ICON
                type: 'error',
                // TITLE
                title: 'Delete',
                // BODY
                text: 'Are you sure that you want to delete this question?',
                // SHOW BUTTONS
                showConfirmButton: true,
                showCancelButton: true,
                // BUTTON TEXT
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                // BUTTON COLOR - BOOTSTRAP RED
                confirmButtonColor: '#dc3545',
                // MESSAGE POSITION
                position: 'center',
                // MESSAGE TO DISSAPEAR IN
                timer: false,
                // COMPACT MESSAGE
                toast: false,
                // PREVENT MESSAGE DISMISAL FROM AN OUTSIDE CLICK
                allowOutsideClick: false
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
                this.$emit('reloadQuestions');
            })
            .catch(error => {});
        },

        deleteController(){
            // RUN DELETE MODAL
            this.$swal(this.swalDeleteConfig)
            .then(response => {
                // AFFIRMATIVE ANSWER
                if(response.value){
                    // DELETE QUESTION
                    this.questionDelete();
                }
            });
        },

        questionDelete(){
            this.$http.delete('questions/'+this.question.id)
            .then(response => {
                // RELOAD QUESTIONS ARRAY ON PARENT COMPONENT
                this.$emit('reloadQuestions');
            })
            .catch(error => {});
        },

        closeEdit(){
            // RESETS EDIT VIEW
            this.$emit('closeEdit');
        },
    },
    created(){
        // CLONE QUESTION SO THAT ORIGINAL WOULDN'T BE CHANGED
        this.setQuestionClone();
    }
}
</script>
