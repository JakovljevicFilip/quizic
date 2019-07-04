<template>

</template>
<script>
// EVENT BUS
import {EventBus} from '../../app';

export default {
    // PASSED FROM Game
    props: ['username', 'score'],
    data(){
        return{
            title: '',
            swalTimeIsUpConfig: {
                // ICON
                type: 'warning',
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
            // SET MODAL TITLE
            this.swalTimeIsUpConfig.title  = title;
            // SET MODAL BODY
            this.swalTimeIsUpConfig.html = 'You\'ve lost!<br>Playing as: <strong>'+this.username+'</strong><br>Score: <strong>'+this.score+'</strong>';
        },

        showModal(){
            console.log(this.swalTimeIsUpConfig);
            // RUN MODAL
            this.$swal(this.swalTimeIsUpConfig)
            .then(response => {
                // NEW GAME
                if(response.value){
                    // RUN startNewGame BUS METHOD ON Game
                    EventBus.$emit('startNewGame');
                }
                else{
                    // GO BACK TO MENU
                    this.$router.push('/menu');
                }
            });
        }
    },

    created(){
        EventBus.$on('gameModal', data => {
            console.log(data);
            // SET MODAL INFO
            this.setModalInfo(data.title);
            // SHOW MODAL
            this.showModal();
        });
    },
}
</script>
