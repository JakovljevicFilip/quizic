import VueRouter from 'vue-router'

// PAGES
// MAIN APPLICATION PAGE
import App from './views/App'

// AUTHENTICATION
import Authentication from './views/Authentication/Authentication'
import Login from './views/Authentication/Login'
import Register from './views/Authentication/Register'

// MENU
import GuestMenu from './views/Menu/GuestMenu'
import UserMenu from './views/Menu/UserMenu'
import AdminMenu from './views/Menu/AdminMenu'


// QUESTION
import QuestionIndex from './views/Question/QuestionIndex'

// ERROR
import PageNotFound from './views/Error/PageNotFound'
import ForbiddenAccess from './views/Error/ForbiddenAccess'

const router = new VueRouter({
    // GETS RID OF THE # IN THE URL
    mode: 'history',
    routes: [
    		// AUTHENTICATION
    		{
            path:'/',
            component: Authentication,
            // REGISTER AND LOGIN ARE ADDED AS CHILDREN SINCE THEY'RE BEING LOADED ON THE SAME COMPONENT
            // THEY'RE BEING LOADED WITHIN ROUTER-VIEW TAG IN Authentication.vue FILE
            children: [
            	{
            		path:'/',
            		redirect: 'login'
            	},
            	{
            		path:'/login',
            		name:'login',
            		component: Login
            	},
            	{
            		path:'/register',
            		name:'register',
            		component: Register
            	}
            ]
        },

        // MENU
        // ADMIN MENU
        {
            path: '/menu',
            name: 'menu.admin',
            component: AdminMenu,
            meta: {
                // ACESSABLE TO ALL USERS
                auth: {
                    'role':2,
                    // IN CASE A GUEST IS TYRING TO ACCESS
                    'redirect':{
                        // SENDS THEM TO A GUEST MENU
                        'name':'menu.guest'
                    },
                    // IN CASE USER IS NOT AN ADMINISTRATOR
                    'forbidenRedirect':{
                    		// SENDS THEM TO A USER MENU
                        'name':'menu.user'
                    }
                }
            }
        },
        // USER MENU
        {
            path: '/menu',
            name: 'menu.user',
            component: UserMenu,
        },
        // GUEST MENU
        {
            path: '/menu',
            name: 'menu.guest',
            component: GuestMenu,
        },

        // QUESTION
        {
            path:'/question',
            name:'question.index',
            component: QuestionIndex,
            meta: {
                // ONLY ACCESSABLE TO ADMINISTRATOR
                auth: {
                    // BACK-END VERIFICATION FOR AN ADMINISTRATOR
                    'role':2,
                    // GUEST IS TRYING TO ACCESS THIS PAGE
                    'redirect':{
                        // SENDS THEM TO A LOGIN PAGE
                        'name':'/login'
                    },
                    // REGULAR USER IS TRYING TO ACCESS THIS PAGE
                    'forbidenRedirect':{
                        // SENDS THEM TO A FORBIDEN ACCESS PAGE
                        'name':'/403'
                    }

                }
            }
        },

        // ERROR
        // 403 - FORBIDDEN ACCESS
        {
            path:'*/403',
            name:'/403',
            component: ForbiddenAccess

        },
        // 404 - PAGE NOT FOUND
        // HAS TO BE THE LAST
        {
            path: "*",
            component: PageNotFound
        }
    ],
});

// EXPORTS COMPONENT
export default router