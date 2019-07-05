// SETS VUE GLOBALY
window.Vue = Vue

// PACKAGES
import './bootstrap';
import 'es6-promise/auto';
import Vue from 'vue';
import VueAuth from '@websanova/vue-auth';
import VueResource from 'vue-resource';
import VueRouter from 'vue-router';
import VeeValidate from 'vee-validate';
import { Validator } from 'vee-validate';
import VueSweetalert2 from 'vue-sweetalert2';
import VTooltip from 'v-tooltip';


// CONFIGURATIONS
import auth from './config/vue-auth';
import router from './config/vue-router';
import validate from './config/VeeValidate.js';
import sweetAlert2 from './config/sweetAlert2';
import './config/vue-resource';


// REGISTRATIONS
// VueRouter
Vue.router = router;
Vue.use(VueRouter);
// VueAuth
Vue.use(VueAuth, auth);
Vue.use(VueResource);
// PREFIX FOR API ROUTES
Vue.http.options.root = '/api/';
// VeeValidate
Vue.use(VeeValidate);
Validator.localize('en', validate);
// SweetAlert
Vue.use(VueSweetalert2, sweetAlert2);
// v-tooltip
Vue.use(VTooltip);

// MAIN APPLICATION PAGE
import App from './views/App';
Vue.component('App', App);

// EVENT BUS
export const EventBus = new Vue();

// SETS UP VUE
const app = new Vue({
  el: '#app',
  components: { App },
  router,
});

