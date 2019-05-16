// SETS VUE GLOBALY
window.Vue = Vue

// PACKAGES
import './bootstrap';
import 'es6-promise/auto';
// import axios from 'axios';
import Vue from 'vue';
import VueAuth from '@websanova/vue-auth';
import VueRouter from 'vue-router';
import VeeValidate from 'vee-validate';
import { Validator } from 'vee-validate';
import VueSweetalert2 from 'vue-sweetalert2';


// CONFIGURATIONS
import auth from './config/auth';
import router from './config/router';
import validate from './config/validate.js';
import sweetAlert2 from './config/sweetAlert2';
import './config/resource';


// REGISTRATIONS
// VueRouter
Vue.router = router;
Vue.use(VueRouter);
// VueAuth
Vue.use(VueAuth, auth);
// VeeValidate
Vue.use(VeeValidate);
Validator.localize('en', validate);
// SweetAlert
Vue.use(VueSweetalert2, sweetAlert2);

// MAIN APPLICATION PAGE
import App from './views/App';
Vue.component('App', App);

// SETS UP VUE
const app = new Vue({
  el: '#app',
  components: { App },
  router,
});
