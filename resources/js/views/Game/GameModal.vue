<template>

</template>
<script>
import {EventBus} from '../../app';

export default {
    props: ['username', 'score'],
    data(){
        return{
            title: '',
            swalTimeIsUpConfig: {
                // ICON
                type: 'info',
                // TITLE
                title: '',
                // BODY
                html: '',
                // SHOW BUTTONS
                showConfirmButton: true,
                showCancelButton: true,
                // BUTTON TEXT
                confirmButtonText: 'New game',
                cancelButtonText: 'Quit',
                // BUTTON COLOR - BOOTSTRAP RED
                // confirmButtonColor: '#dc3545',
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

    methods:{
        setModalInfo(title){
            this.swalTimeIsUpConfig.title  = title;
            this.swalTimeIsUpConfig.html = 'You\'ve lost!<br>Playing as: <strong>'+this.username+'</strong><br>Score: <strong>'+this.score+'</strong>';
        },

        showModal(){
            // RUN MODAL
            this.$swal(this.swalTimeIsUpConfig)
            .then(response => {
                // NEW GAME
                if(response.value){
                    EventBus.$emit('startNewGame');
                }
                else{
                    this.$router.push('/menu');
                }
            });
        }
    },

    created(){
        EventBus.$on('gameModal', data => {
            this.setModalInfo(data.title);
            this.showModal();
        });
    },
}
</script>
