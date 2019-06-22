<template>
<div class="d-flex justify-content-center animated slideInDown container-game--transparent game-hints">
    <div class="container-hint d-flex justify-content-center">
        <!-- 50:50 -->
        <button class="btn-hint mr-5" @click="half" :disabled="disabled.half">
            <i class="fas fa-times"></i>
        </button>

        <!-- CHANGE -->
        <button class="btn-hint mr-5" @click="change" :disabled="disabled.change">
            <i class="fas fa-exchange-alt"></i>
        </button>

        <!-- SOLVE -->
        <button class="btn-hint" @click="solve" :disabled="disabled.solve">
            <i class="fas fa-check"></i>
        </button>
    </div>
</div>
</template>

<script>
import {EventBus} from '../../app';

export default {
    data(){
        return {
            disabled: {
                half: false,
                change: false,
                solve: false,
            }
        }
    },
    methods: {
        half(){
            this.disabled.half = true;
            EventBus.$emit('half');
        },
        change(){
            this.disabled.change = true;
        },
        solve(){
            this.disabled.solve = true;
        },
    }
}
</script>

<style>

</style>
