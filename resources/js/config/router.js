import VueRouter from 'vue-router'

// AUTHENTICATION
// LAYOUT COMPONENT
import Authentication from '../views/Authentication/Authentication'
import Login from '../views/Authentication/Login'
import Register from '../views/Authentication/Register'

// MENU
import MenuUser from '../views/Menu/MenuUser'
import MenuAdmin from '../views/Menu/MenuAdmin'

// DIFFICULTY
import DifficultyIndex from '../views/Difficulties/DifficultyIndex';

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
                    name:'menu.admin'
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
            // ADMIN MENU
            {
                path: '/menu',
                name: 'menu.admin',
                component: MenuAdmin,
                meta:{
                    auth:{
                        // USER HAS TO HAVE A ROLE 2, HAS TO BE THE ADMIN TO ACCESS
                        roles:2,
                        // IF AUTHENTICATION TOKEN IS MISSING
                        authRedirect:{
                            name:'login',
                        },
                        // IF USER DOESN'T HAVE PERMISION TO ACCES ADMIN MENU
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
                component: MenuUser,
            },

            // DIFFICULTY
            {
                path: '/difficulty',
                name: 'difficulty',
                component: DifficultyIndex,
                meta: {
                    auth:{
                        roles: 2,
                        authRedirect: {
                            name: 'login'
                        },
                        forbiddenRedirect: {
                            name: '/403'
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
