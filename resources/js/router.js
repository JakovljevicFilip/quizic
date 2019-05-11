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
            // CONTROLLER
            {
                path:'/',
                redirect:{
                    name:'menu.admin'
                }
            },

    		// AUTHENTICATION
    		{
                path:'/authentication',
                name:'authentication',
                component: Authentication,
                // meta:{
                //     auth:undefined,
                //     redirect:{
                //         name:'admin.menu'
                //     },
                // },
                // REGISTER AND LOGIN ARE ADDED AS CHILDREN SINCE THEY'RE BEING LOADED ON THE SAME COMPONENT
                // THEY'RE BEING LOADED WITHIN ROUTER-VIEW TAG IN Authentication.vue FILE
                children: [
                    // {
                    //     path:'/',
                    //     redirect:'/login',
                    // },
                	{
                		path:'/login',
                		name:'login',
                		component: Login,
                	},
                	{
                		path:'/register',
                		name:'register',
                		component: Register,
                	}
                ],
            },

            // MENU
            // ADMIN MENU
            {
                path: '/menu',
                name: 'menu.admin',
                component: AdminMenu,
                meta:{
                    auth:{
                        roles:2,
                        authRedirect:{
                            name:'login',
                        },
                        forbiddenRedirect:{
                            name:'menu.user',
                        },
                    }
                }
            },
            // USER MENU
            {
                path: '/menu',
                name: 'menu.user',
                component: UserMenu,
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
                        roles:2,
                        // GUEST IS TRYING TO ACCESS THIS PAGE
                        redirect:{
                            // SENDS THEM TO A LOGIN PAGE
                            name:'/login'
                        },
                        // REGULAR USER IS TRYING TO ACCESS THIS PAGE
                        forbiddenRedirect:{
                            // SENDS THEM TO A FORBIDEN ACCESS PAGE
                            name:'/403'
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
