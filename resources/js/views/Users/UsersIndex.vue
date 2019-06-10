<template>
	<div class="container visible container-index--outer">

        <div class="text-center pb-5">
            <img src="/img/logo.png" alt="logo" class="logo--height">
        </div>

        <div class="text-white lead p-3 container-index--inner">

            <!-- HIDE USERS CONTATINER IF THERE ARE NO QUESTIONS -->
            <div v-if="users.length !== 0 ">
                <div class="screen-md-show">
                    <div class="user-grid-nav">
                        <div class="lead user-grid-username">Username</div>
                        <div class="lead text-center user-grid-role">User/Administrator</div>
                        <div class="lead text-center user-grid-delete">Delete</div>
                    </div>
                </div>

                <div class="screen-sm-show">
                    <div class="text-center">Users</div>
                    <div class="d-flex">
                        <div class="flex-grow-1 text-center">User/Administrator</div>
                        <div class="flex-grow-1 text-center">Delete</div>
                    </div>
                </div>


                <div v-for="(user, index) in users" :key="index" class="animated slideInDown my-3 wrapper--md-and-down user-grid-show">
                        <p class="lead p-2 my-auto text-center-md user-grid__username wrapper--md-and-up">{{ user.username }}</p>
                        <div class="d-flex user-grid__status">
                            <div class="m-auto icon__switch" :class="{
                                'icon__switch--inactive' : user.role === 1,
                                'icon__switch--active' : user.role === 2,
                            }" @click="changeRole(user)">
                                <div class="icon__slider" :class="{
                                    'icon__slider--inactive' : user.role === 1,
                                    'icon__slider--active' : user.role === 2
                                }"></div>
                            </div>
                        </div>
                        <div class="text-center user-grid__delete icon">
                            <i class="fas fa-times icon icon__times" @click="deleteUser(user.id)"></i>
                        </div>
                </div>

                <div class="d-flex justify-content-center">
                    <button class="btn text-center w-auto btn__main" @click="loadMore()" :disabled="!isNotOnLastPage">{{ loadButtonText }}</button>
                </div>
            </div>
        </div>

        <div class="text-white text-center">
            <i class="fas fa-long-arrow-alt-left icon icon__back" @click="goBack" alt="back"></i>
        </div>
    </div>
</template>

<script>
export default {
    computed: {
        isNotOnLastPage: function(){
            return this.pagination.page <= this.pagination.last_page;
        },
        loadButtonText: function(){
            return this.pagination.page <= this.pagination.last_page ? 'Load more users' : 'No more users';
        },
    },
    data(){
        return{
            users: [],
            // DATA USED FOR FETCHING USERS
            pagination: {
                // CURRENT PAGE FOR PAGINATION
                page: 1,
                // NUMBER OF QUESTIONS BEING FETCHED PER REQUEST
                per_page: 15,
                // LAST PAGE FOR PAGIANTION
                last_page: null,
            },
        }
    },
    methods: {
        getUsers(append){
            this.$http.get('users',{
                params: {
                    'page': this.pagination.page,
                    'per_page': this.pagination.per_page,
                }
            })
            .then(response =>{
                // SHOULD APPEND TO USERS
                if(append){
                    // APPEND NEW USERS TO USERS ARRAY
                    this.users = this.users.concat(response.body.users);
                }
                // SHOULD RESET USERS ARRAY
                else{
                    // RESET USERS ARRAY
                    this.users = response.body.users;
                }
                // SET LAST PAGE
                this.pagination.last_page = response.body.last_page;
                // INCREMENT CURRENT PAGE
                this.pagination.page++;
            })
            .catch(error => {});
        },
        changeRole(user){
            // USER WHO'S ROLE IS BEING CHANGED
            let id = user.id;
            // NEW USER ROLE
            let role = this.setNewRole(user.role);

            this.$http.patch('users/role',{
                user: {
                    id: id,
                    role: role,
                }
            })
            .then(response =>{
                // USER IS NO LONGER ADMINISTRATOR
                if(response.data.logout){
                    // LOGOUT
                    this.$auth.logout();
                }
                // RESET PAGINATION
                this.resetPagination();
                //RELOAD USERS
                this.getUsers();

            })
            .catch(error => {});
        },
        setNewRole(role){
            // SWITCH BETWEEN 1 AND 2 FOR USER ROLE
            return role === 1 ? 2 : 1;
        },
        resetPagination(){
            this.pagination = {
                page: 1,
                per_page: 15,
                last_page: null,
            }
        },
        deleteUser(id){
            this.$http.delete('users',{
                params:{
                    id: id,
                }
            })
            .then(response =>{
                // USER IS NO LONGER ADMINISTRATOR
                if(response.data.logout){
                    // LOGOUT
                    this.$auth.logout();
                }
                // RESET PAGINATION
                this.resetPagination();
                //RELOAD USERS
                this.getUsers();

            })
            .catch(error => {});
        },
        loadMore(){
            // GET USERS AND APPEND TO ARRAY
            this.getUsers(true);
        },
        goBack(){
            // GO BACK TO MENU
            this.$router.push('/menu');
        }
    },
    created(){
        // GET USERS
        this.getUsers();
    }
}
</script>

<style>

</style>

