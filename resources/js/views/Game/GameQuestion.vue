<template>
    <div>
        <!-- QUESTION TEXT -->
        <div class="lead my-auto p-3 game-question question__text mx-auto game-appear-1" ref="question">{{ question.text }}</div>
        <!-- QUESTION ANSWERS -->
        <GameAnswers :answers="question.answers"></GameAnswers>
    </div>
</template>

<script>
// COMPONENTS
import GameAnswers from './GameAnswers';

// EVENT BUS
import {EventBus} from '../../app';

export default {
    // PASSED FROM Game
    props: ['question'],
    // REGISTER COMPONENTS
    components: {
        GameAnswers,
    },
}
</script>

<style>

</style>
