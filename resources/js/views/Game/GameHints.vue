<template>
<div class="d-flex justify-content-center animated slideInDown container-game--transparent game-hints">
    <div class="container-hint d-flex justify-content-center">
        <button class="btn-hint mr-5">
            <i class="fas fa-times"></i>
        </button>

        <button class="btn-hint mr-5">
            <i class="fas fa-exchange-alt"></i>
        </button>

        <button class="btn-hint">
            <i class="fas fa-check"></i>
        </button>
    </div>
</div>
</template>

<script>
import {EventBus} from '../../app';

export default {
    created(){
        EventBus.$on('sent', (data) =>{
            console.log(data.information1);
        });
    }
}
</script>

<style>

</style>
