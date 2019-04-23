import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import App from './views/App'
import Hello from './views/Hello'
import Menu from './views/Menu'
import PageNotFound from './views/PageNotFound'
import QuestionIndex from './views/Question/QuestionIndex'

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'menu',
            component: Menu
        },
        {
            path: '/hello',
            name: 'hello',
            component: Hello,
        },
        {
            path:'/question',
            name:'question.index',
            component: QuestionIndex
        },
        // 404 - HAS TO BE LAST
        {
            path: "*",
            component: PageNotFound
        }
    ],
});

const app = new Vue({
    el: '#app',
    components: { App },
    router,
});