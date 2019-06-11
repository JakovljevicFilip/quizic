<template>
    <div class="text-center user-grid__delete icon">
        <i class="fas fa-times icon icon__times" @click="controllerDelete()"></i>
    </div>
</template>

<script>
export default {
    props: ['user'],
    data(){
        return {
            // DELETE MODAL CONFIGURATION
            swalConfigDelete: {
                // ICON
                type: 'error',
                // TITLE
                title: 'Delete',
                // BODY
                text: 'Are you sure you want to delete "'+this.user.username+'"?',
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
        }
    },
    methods: {
        controllerDelete(){
            // RUN DELETE MODAL
            this.$swal(this.swalConfigDelete)
            .then(response => {
                // AFFIRMATIVE ANSWER
                if(response.value){
                    // DELETE USER
                    this.userDelete();
                }
            });
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
                    this.$emit('usersReload');
                }
            })
            .catch(error => {});
        },
    },
}
</script>

<style>

</style>
