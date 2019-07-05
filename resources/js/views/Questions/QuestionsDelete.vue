<template>
    <i class="fas fa-times icon icon__times ml-3" @click="deleteController" v-tooltip.bottom="'Delete question'"></i>
</template>

<script>
// EVENT BUS
import {EventBus} from '../../app';

export default {
    // PASSED FROM QuestionIndex
    props: ['question'],
    data(){
        return {
            // DELETE MODAL CONFIGURATION
            modalInformations: {
                type: 'error',
                title: 'Delete',
                text: 'Are you sure you want to delete this question?',
                // BUTTON COLOR - BOOTSTRAP RED
                confirmButtonColor: '#dc3545',
                origin: 'questionDelete',
            },
        }
    },
    methods:{
        deleteController(){
            // RUN DELETE MODAL
            EventBus.$emit('showModal',this.modalInformations);
        },

        questionDelete(){
            // RUN DELETE REQUEST
            this.$http.delete('questions',{
                params:{
                    'id': this.question.id
                }
            })
            .then(response => {
                // RUN reloadQuestions BUS METHOD ON QuestionsIndex
                EventBus.$emit('reloadQuestions');
            })
            .catch(error => {});
        },
    },

    created(){
        // EVENT BUS
        EventBus.$on('questionDelete', this.questionDelete);
    },

    beforeDestroy(){
        // NECESSARY SINCE COMPONENT IS BEING RELOADED ON CHANGE
        EventBus.$off('questionDelete');
    },
}
</script>
