<template>
    <i class="fas fa-check icon icon__confirm mr-3"></i>
</template>

<script>
import {EventBus} from '../../app';

export default {
    props: ['question'],
    methods:{
        questionUpdate(){
            // QUESTIONS UPDATE REQUEST
            this.$http.put('questions', this.question)
            .then(response => {
                // RELOAD QUESTIONS ARRAY ON PARENT COMPONENT
                EventBus.$emit('reloadQuestions');
            })
            .catch(error => {});
        },
    },
    created(){
        // RUN questionsUpdate BUS METHOD ON GameIndex
        EventBus.$on('questionUpdate', this.questionUpdate);
    },

    beforeDestroy () {
        // NECESSARY SINCE COMPONENT IS BEING RELOADED ON CHANGE
        EventBus.$off('questionUpdate', this.questionUpdate)
    },
}
</script>

<style>

</style>
