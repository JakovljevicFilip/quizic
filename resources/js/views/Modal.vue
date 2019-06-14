<template>

</template>
<script>
// EVENT BUS
import {EventBus} from '../app';

export default {
    data(){
        return{
            // SweetAlert/MODAL CONFIGURATION
            swalConfig: {
                // ICON
                type: 'info',
                // TITLE
                title: '',
                // BODY
                html: '',
                text: '',
                // SHOW BUTTONS
                showConfirmButton: true,
                showCancelButton: true,
                // BUTTON TEXT
                confirmButtonText: 'Confirm',
                cancelButtonText: 'Cancel',
                // MESSAGE POSITION
                position: 'center',
                // MESSAGE TO DISSAPEAR IN
                timer: false,
                // COMPACT MESSAGE
                toast: false,
                // PREVENT MESSAGE DISMISAL FROM AN OUTSIDE CLICK
                allowOutsideClick: false
            },

            // USED WITH responses TO CALL CORRECT METHODS ON CONFIRM/CANCEL
            origin: '',
            reponses: {
                showGameModal: {
                    true: 'startNewGame',
                    false: 'goBack',
                },
                error: {
                    true: 'goBack',
                },
                questionDelete: {
                    true: 'questionDelete',
                    false: false,
                },
            },
        }
    },

    methods:{
        setModalInfo(data){
            // LOOP THROUGH DATA
            for (const information in data) {
                // MODAL ORIGIN
                if(information === 'origin'){
                    // SET MODAL ORIGIN
                    this.origin = data[information];
                }
                // MODAL INFORMATION
                else{
                    // SET MODAL INFROMATION
                    this.swalConfig[information] = data[information];
                }
            }
        },

        showModal(){
            const origin = this.reponses[this.origin];

            // RUN MODAL
            this.$swal(this.swalConfig)
            .then(response => {
                // NEW GAME
                if(response.value){
                    // RUN APPROPRIATE BUS METHOD
                    EventBus.$emit(origin.true);
                }
                else{
                    // SOMETHING IS SUPPOSSED TO HAPPEN ON CANCEL
                    if(origin.false){
                        // GO BACK TO MENU
                        EventBus.$emit(origin.false);
                    }
                }
            });
        }
    },

    created(){
        EventBus.$on('showModal', data => {
            // SET MODAL INFO
            this.setModalInfo(data);
            // SHOW MODAL
            this.showModal();
        });
    },
}
</script>
