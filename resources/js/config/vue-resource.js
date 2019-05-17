import Vue from 'vue';
import VueResource from 'vue-resource';

Vue.use(VueResource);
// SET API ROOT
Vue.http.options.root = '/api';

// SET X-CSRF TOKEN
let token = document.head.querySelector('meta[name="csrf-token"]');
Vue.http.headers.common['X-CSRF-TOKEN'] = token.content;

Vue.http.interceptors.push(function(request) {
    // return response callback
    return function(response) {
        if(response.status >= 200 && response.status < 300){
            interceptorHandler.handleResponse(response, true);
        }
        else{
            interceptorHandler.handleResponse(response, false);
        }
    };
});

  // HANDLES EVERY AXIOS REQUEST RESPONSE
let interceptorHandler = {
    // DETERMINES MESSAGE ICON FOR sweetAlert
    messageType: null,

    // VALID RESPONSE ENTRY POINT
    handleResponse: (response, status) => {
        // ATTEMPT TO GET MESSAGES
        let message = response.body.message;

        // THERE ARE MESSAGES
        if(message !== undefined){
            // WHICH TYPE OF MESSAGE IS SUPPOSSED TO BE SHOWN
            status ? interceptorHandler.messageType = 'success' : interceptorHandler.messageType = 'error';
            // DETERMINE MESSAGES FORMAT
            interceptorHandler.checkType(message);
        }

    },

    // FORMAT IN WHICH MESSAGES ARE PROVIDED
    checkType: message => {

        // MESSAGES IS AN OBJECT
        if(typeof message === 'object'){
            interceptorHandler.handleObject(message);
        }

        // MESSAGES IS AN ARRAY
        else if(typeof message === 'array'){
            interceptorHandler.handleArray(message);
        }

        // MESSAGE IS A STRING
        else if(typeof message === 'string'){
            interceptorHandler.writeMessage(message);
        }

        // MESSAGE IS SOMETHING ELSE
        // ISN'T SUPPOSED TO HAPPEN
        else{
            interceptorHandler.handleOther();
        }
    },

    handleObject: message => {
        // TEMPORARY VARIABLE TO STORE NEWLY FORMATED MESSAGE
        let messageToPassFurther = '';

        // LOOP THROUGH MESSAGES
        for(let singleMessage in message){
            messageToPassFurther+=message[singleMessage]+'<br>';
        }
        // REASIGN MESSAGES VARIABLE
        message = messageToPassFurther;
        interceptorHandler.writeMessage(message);
    },

    handleArray: message => {
        // TEMPORARY VARIABLE TO STORE NEWLY FORMATED MESSAGE
        let messageToPassFurther = '';

        // LOOP THROUGH MESSAGES
        for(i=0; i < message.length; i++){
            // STORE MESSAGES INTO STRING
            messageToPassFurther+=message[i]+'<br>';
        }

        // REASIGN MESSAGES VARIABLE
        message = messageToPassFurther;
        interceptorHandler.writeMessage(message);
    },

    handleOther: () => {
        console.log('Error: Message has not been recognized.');
        // END OF FUNCTION
        return;
    },

    // WRITE MESSAGE
    writeMessage: message => {
        // WRITES MESSAGE VIA sweetAlert
        Vue.swal('Login', message, interceptorHandler.messageType);
        // END OF FUNCTION
        return;
    }
}
