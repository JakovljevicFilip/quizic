<template>
	<div class="container visible container-index__scroll">

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
                            }" @click="controllerChangeRole(user)">
                                <div class="icon__slider" :class="{
                                    'icon__slider--inactive' : user.role === 1,
                                    'icon__slider--active' : user.role === 2
                                }"></div>
                            </div>
                        </div>
                        <div class="text-center user-grid__delete icon">
                            <i class="fas fa-times icon icon__times" @click="controllerDelete(user)"></i>
                        </div>
                </div>

                <div class="d-flex justify-content-center">
                    <button class="btn text-center w-auto btn__main" @click="loadMore()" :disabled="!isNotOnLastPage">{{ loadButtonText }}</button>
                </div>
            </div>
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
            // ALL USERS
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
            // USER THAT IS BEING ALTERED
            user: {},
            // DELETE MODAL CONFIGURATION
            swalConfigDelete: {
                // ICON
                type: 'error',
                // TITLE
                title: 'Delete',
                // BODY
                text: 'Are you sure you want to delete "',
                // SHOW BUTTONS
                showConfirmButton: true,
                showCancelButton: true,
                // BUTTON TEXT
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                // BUTTON COLOR - BOOTSTRAP RED
                confirmButtonColor: '#dc3545',
                // MESSAGE POSITION
                position: 'center',
                // MESSAGE TO DISSAPEAR IN
                timer: false,
                // COMPACT MESSAGE
                toast: false,
                // PREVENT MESSAGE DISMISAL FROM AN OUTSIDE CLICK
                allowOutsideClick: false
            },

            // REMOVING YOURSELF MODAL CONFIGURATION
            swalConfigChangeRole: {
                type: 'warning',
                title: 'You are about to remove yourself as an administrator',
                html: 'If you proceed you will be logged out and will no longer have access to administrator priviledges.<br> Do you want to proceed?',
                showConfirmButton: true,
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                confirmButtonColor: '#dc3545',
                position: 'center',
                timer: false,
                toast: false,
                allowOutsideClick: false
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

        controllerChangeRole(user){
            // SET USER THAT IS BEING ALTERED
            this.userSetAltered(user);

            // USER IS CHANGING THEIR OWN ROLE
            if(this.user.id === this.$auth.user().id){
                // RUN MODAL
                this.userChangeRoleModal();
            }

            // ANOTHER USER IS BEING ALTERED
            else{
                // CHANGE ROLE
                this.userChangeRoleMethod();
            }
        },

        userSetAltered(user){
            this.user = {
                id: user.id,
                role: user.role,
            }
        },

        userChangeRoleModal(){
            // RUN WARNING MODAL
            this.$swal(this.swalConfigChangeRole)
            .then(response => {
                // AFFIRMATIVE ANSWER
                if(response.value){
                    // CHANGE USER ROLE USER
                    this.userChangeRoleMethod();
                }
            });
        },

        userChangeRoleMethod(){
            // NEW USER ROLE
            this.user.role = this.setNewRole(this.user.role);

            this.$http.patch('users/role',{
                user: {
                    id: this.user.id,
                    role: this.user.role,
                }
            })
            .then(response =>{
                // USER CHANGED THEIROWN ROLE AND IS NO LONGER AN ADMINISTRATOR
                if(response.data.logout){
                    // LOGOUT
                    this.$auth.logout();
                }
                // USER CHANGED SOMEONE ELSE
                else{
                    // RELOAD USERS
                    this.usersReload();
                }
            })
            .catch(error => {});
        },

        setNewRole(role){
            // SWITCH BETWEEN 1 AND 2 FOR USER ROLE
            return role === 1 ? 2 : 1;
        },

        usersReload(){
            // RESET PAGINATION
            this.resetPagination();
            // RESET USER THAT IS BEING ALTERED
            this.user = {};
            //RELOAD USERS
            this.getUsers();

        },

        resetPagination(){
            this.pagination = {
                page: 1,
                per_page: 15,
                last_page: null,
            }
        },

        controllerDelete(user){
            // ADD USERNAME TO THE MODAL TEXT
            this.swalConfigDelete.text += user.username+'"?';

            // RUN DELETE MODAL
            this.$swal(this.swalConfigDelete)
            .then(response => {
                // AFFIRMATIVE ANSWER
                if(response.value){
                    // SET USER FOR DELITION
                    this.userSetAltered(user);
                    // DELETE USER
                    this.userDelete();
                    // RESET USER THAT IS BEING ALTERED
                    this.user = {};
                }
            });

            // RESET MODAL TEXT
            this.swalConfigDelete.text = 'Are you sure you want to delete "';
        },

        userDelete(){
            this.$http.delete('users',{
                params:{
                    id: this.user.id,
                }
            })
            .then(response =>{
                // USER DELETED THEMSELF AS ADMINISTRATOR
                if(response.data.logout){
                    // LOGOUT
                    this.$auth.logout();
                }
                // USER DELETED SOMEONE ELSE
                else{
                    // RELOAD USERS
                    this.usersReload();
                }
            })
            .catch(error => {});
        },

        loadMore(){
            // GET USERS AND APPEND TO ARRAY
            this.getUsers(true);
        },
    },
    created(){
        // GET USERS
        this.getUsers();
    }
}
</script>

<style>

</style>

