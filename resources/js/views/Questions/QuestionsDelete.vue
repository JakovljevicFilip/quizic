<template>
    <i class="fas fa-times icon icon__times ml-3" @click="deleteController"></i>
</template>

<script>
import {EventBus} from '../../app';

export default {
    props: ['question'],
    data(){
        return {
            // DELETE MODAL CONFIGURATION
            swalDeleteConfig: {
                // ICON
                type: 'error',
                // TITLE
                title: 'Delete',
                // BODY
                text: 'Are you sure you want to delete this question?',
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
    methods:{
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
                EventBus.$emit('reloadQuestions');
            })
            .catch(error => {});
        },
    },
}
</script>

<style>

</style>
