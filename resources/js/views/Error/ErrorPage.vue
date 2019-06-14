<template>

</template>

<script>
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
            swalConfig: {
                // ICON
                type: 'error',
                // TITLE
                title: 'Error - ',
                // BODY
                text: 'Error message.',
                // SHOW BUTTON
                showConfirmButton: true,
                // MESSAGE POSITION
                position: 'center',
                // MESSAGE TO DISSAPEAR IN
                timer: false,
                // COMPACT MESSAGE
                toast: false,
                // BUTTON TEXT
                confirmButtonText: 'Go Back',
                // PREVENT MESSAGE DISMISAL FROM AN OUTSIDE CLICK
                allowOutsideClick: false
            }
        }
    },
    methods: {
        setMessage(){
            // GET ROUTE NAME, ie. 404
            let name = this.$route.name;
            // SET MESSAGE TITLE, ie. Error - 404
            this.swalConfig.title += name;
            // SET MESSAGE BODY, ie. Resource not found.
            this.swalConfig.text = this.messages[name];
            // MESSAGE DOESN'T EXIST
            if(this.swalConfig.text === undefined){
                this.swalConfig.text = 'Unknown server error';
            }
        },

        writeMessage(){
            // DISPLAY MESSAGE
            Vue.swal(this.swalConfig)
            .then(this.goBack);
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
    }
}
</script>
