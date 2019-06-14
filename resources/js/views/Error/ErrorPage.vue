<template>

</template>

<script>
import {EventBus} from '../../app';
export default {
    data(){
        return{
            // ERROR CODE
            code: 500,
            // MESSAGES FOR ERROR CODES
            messages: {
                404: 'Resource not found.',
                403: 'Access forbidden. Try to login again.',
                500: 'Internal server error.',
            },
            // DISPLAYING MESSAGE
            modalInformations: {
                type: 'error',
                title: 'Error - ',
                text: 'Error message.',
                showCancelButton: false,
                origin: 'error',
            }
        }
    },
    methods: {
        setMessage(){
            // GET ROUTE NAME, ie. 404
            let name = this.$route.name;
            // SET MESSAGE TITLE, ie. Error - 404
            this.modalInformations.title += name;
            // SET MESSAGE BODY, ie. Resource not found.
            this.modalInformations.text = this.messages[name];
            // MESSAGE DOESN'T EXIST
            if(this.modalInformations.text === undefined){
                this.modalInformations.text = 'Unknown server error';
            }
        },

        writeMessage(){
            // DISPLAY MESSAGE
            EventBus.$emit('showModal',this.modalInformations);
        },

        goBack(){
            // ATTEMPT TO RETURN TO MENU
            this.$router.push('/menu');
        }
    },
    created(){
        // SET APPROPRIATE MESSAGE
        this.setMessage();
        // WRITE MESSAGE
        this.writeMessage();

        EventBus.$on('goBack', this.goBack);
    }
}
</script>
