<template>
    <div class="lead text-white animated slideInDown game-info container-game--transparent">
        <!-- GAME INFO -->
        <div>Playing as: <strong>{{username}}</strong></div>
        <div>Score: <strong>{{score}}</strong></div>
    </div>
</template>

<script>
export default {
    // PASSED FROM Game
    props: ['username', 'score'],
}
</script>

<style>

</style>
