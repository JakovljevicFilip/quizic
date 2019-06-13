<template>
    <div class="lead text-white animated slideInDown game-info container-game--transparent">
        <div>Playing as: <strong>{{username}}</strong></div>
        <div>Score: <strong>{{score}}</strong></div>
    </div>
</template>

<script>
export default {
    props: ['username', 'score'],
}
</script>

<style>

</style>
