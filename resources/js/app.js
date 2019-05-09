require('./bootstrap');

import 'es6-promise/auto'
import axios from 'axios'
import './bootstrap'
import Vue from 'vue'
import VueAuth from '@websanova/vue-auth'
import VueAxios from 'vue-axios'
import VueRouter from 'vue-router'
// MAIN APPLICATION PAGE
import App from './views/App'
// AUTHENTICATION SETUP
import auth from './auth'
// ROUTER SETUP
import router from './router'

// SETS VUE GLOBALY
window.Vue = Vue

// SETS VUE ROUTER
Vue.router = router
Vue.use(VueRouter)

// SETS VUE AUTHENTICATION
Vue.use(VueAxios, axios)
axios.defaults.baseURL = `${process.env.MIX_APP_URL}/api`
Vue.use(VueAuth, auth)

// LOADS SPA
Vue.component('App', App)

// SETS UP VUE
const app = new Vue({
  el: '#app',
  components: { App },
  router,
});
