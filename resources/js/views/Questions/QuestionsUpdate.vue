<template>
    <i class="fas fa-check icon icon__confirm mr-3"></i>
</template>

<script>
import {EventBus} from '../../app';

export default {
    props: ['question'],
    methods:{
        questionUpdate(){
            this.$http.put('questions', this.question)
            .then(response => {
                // RELOAD QUESTIONS ARRAY ON PARENT COMPONENT
                EventBus.$emit('reloadQuestions');
            })
            .catch(error => {});
        },
    },
    created(){
        EventBus.$on('questionUpdate', this.questionUpdate);
    },

    beforeDestroy () {
        EventBus.$off('questionUpdate', this.questionUpdate)
    },
}
</script>

<style>

</style>
