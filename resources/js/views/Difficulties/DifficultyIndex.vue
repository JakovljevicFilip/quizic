<template>
    <div class="app-wrapper text-white">
        <h3 class="text-center mb-5 heading">Difficulties</h3>
        <input type="text" class="form-control index-input" placeholder="Enter new difficulty">
        <div class="my-3 d-flex index-input" v-for="difficulty in difficulties" :key="difficulty.id">
            <p class="flex-grow-1 m-0" >{{ difficulty.text }}</p>
            <div class="d-flex mx-1 index-icon">
                <i class="fas fa-pen m-auto index-edit"></i>
            </div>
            <div class="d-flex mx-1 index-icon">
                <i class="fas fa-times m-auto index-delete"></i>
            </div>
        </div>
        <div class="text-center">
            <router-link :to="{ name: 'menu.admin' }" class="fas fa-long-arrow-alt-left text-white mt-3 back"></router-link>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return {
            difficulties:[]
        }
    },
    methods:{
        getDifficulties(){
            this.$http.get('difficulty')
            .then(response => {
                this.difficulties = response.body.difficulties;
            })
            .catch(error => {
                console.log(error.body.messages);
            });
        },
        edit(){

        },
        delete(){

        }
    },
    mounted(){
        this.getDifficulties();
    }
}
</script>
<style lang="scss">
    @import "../../../sass/application/index.scss";
</style>
