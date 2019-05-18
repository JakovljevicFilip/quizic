import VueRouter from 'vue-router'

// AUTHENTICATION
// LAYOUT COMPONENT
import Authentication from '../views/Authentication/Authentication'
import Login from '../views/Authentication/Login'
import Register from '../views/Authentication/Register'

// MENU
import Menu from '../views/Menu/Menu';


// QUESTION
import QuestionIndex from '../views/Questions/QuestionIndex'

// ERROR
import PageNotFound from '../views/Errors/PageNotFound'
import ForbiddenAccess from '../views/Errors/ForbiddenAccess'


const router = new VueRouter({
    // GETS RID OF THE # IN THE URL
    mode: 'history',
    routes: [
        // CONTROLLER
        {
            path:'/',
            redirect:{
                name:'menu'
            }
        },

        // AUTHENTICATION
        {
            path:'/authentication',
            name:'authentication',
            component: Authentication,
            children: [
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
        {
            path: '/menu',
            name: 'menu',
            component: Menu,
            meta:{
                auth:{
                    // USER HAS TO BE LOGGED IN
                    roles: true,
                    // IF AUTHENTICATION TOKEN IS MISSING
                    authRedirect:{
                        name:'login',
                    }
                }
            }
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
            // ANYTHING THAT ENDS WITH /403
            path:'*/403',
            name:'/403',
            component: ForbiddenAccess

        },
        // 404 - PAGE NOT FOUND
        // HAS TO BE THE LAST
        {
            // ANY PATH
            path: "*",
            component: PageNotFound
        }

    ],
});

// EXPORTS COMPONENT
export default router
