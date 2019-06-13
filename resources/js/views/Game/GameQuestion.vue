<template>
    <div>
        <div class="lead my-auto p-3 game-question question__text mx-auto game-appear-1" ref="question">{{ question.text }}</div>
        <GameAnswers :answers="question.answers" ref="GameAnswers"></GameAnswers>
    </div>
</template>

<script>
import GameAnswers from './GameAnswers';
import {EventBus} from '../../app';

export default {
    props: ['question'],

    components: {
        GameAnswers,
    },
}
</script>

<style>

</style>
