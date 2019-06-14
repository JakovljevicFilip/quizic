<template>
    <!-- CHANGE ROLE ICON -->
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
</template>

<script>
// EVENT BUS
import {EventBus} from '../../app';

export default {
    props: ['user'],

    computed: {
        newRole: function(){
            // SWITCH BETWEEN 1 AND 2 FOR USER ROLE
            return this.user.role === 1 ? 2 : 1;
        }
    },

    data(){
        return {
            // REMOVING YOURSELF MODAL CONFIGURATION
            modalInformations: {
                type: 'warning',
                title: 'You are about to remove yourself as an administrator',
                html: 'If you proceed you will be logged out and will no longer have access to administrator priviledges.<br> Do you want to proceed?',
                origin: 'userChangingThemself',
            },
        }
    },

    methods:{
        controllerChangeRole(){
            // USER IS TRYING TO CHANGE THEIR OWN ROLE
            if(this.user.id === this.$auth.user().id){
                Vue.swal('Error','You can not change your own role.','error');
            }
            // ANOTHER USER IS BEING ALTERED
            else{
                // CHANGE ROLE
                this.userChangeRole();
            }
        },

        userChangeRole(){
            this.$http.patch('users/role',{
                id: this.user.id,
                role: this.newRole,
            })
            .then(response =>{
                // USER CHANGED THEIROWN ROLE AND IS NO LONGER AN ADMINISTRATOR
                if(response.data.logout){
                    // LOGOUT
                    this.$auth.logout();
                }
                // USER CHANGED SOMEONE ELSE
                else{
                    // RUN usersReload BUS EVENT ON UsersIndex
                    EventBus.$emit('usersReload');
                }
            })
            .catch(error => {});
        },
    },
}
</script>

<style>

</style>
