import VueRouter from 'vue-router';

// AUTHENTICATION
// LAYOUT COMPONENT
import Authentication from '../views/Authentication/Authentication';
import Login from '../views/Authentication/Login';
import Register from '../views/Authentication/Register';

// MENU
import Menu from '../views/Menu/Menu';


// QUESTIONS
import QuestionsIndex from '../views/Questions/QuestionsIndex';
import QuestionsCreate from '../views/Questions/QuestionsCreate';

// USERS
import UsersChangePassword from '../views/Users/UsersChangePassword';
import UsersIndex from '../views/Users/UsersIndex';

// GAME
import Game from '../views/Game/Game';

// HIGHSCORES
import HighscoresIndex from '../views/Highscores/HighscoresIndex';

// ERROR
import ErrorPage from '../views/Error/ErrorPage';


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
            redirect: 'login',
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
                auth: true
            }
        },

        // QUESTION
        {
            path:'/questions',
            name:'questions.index',
            component: QuestionsIndex,
            meta: {
                // ONLY ACCESSABLE TO ADMINISTRATOR
                auth: {
                    // BACK-END VERIFICATION FOR AN ADMINISTRATOR
                    roles:2,
                    // GUEST IS TRYING TO ACCESS THIS PAGE
                    redirect: 'questions/403',
                }
            }
        },
        {
            path:'/questions/create',
            name:'questions.create',
            component: QuestionsCreate,
            meta: {
                // ONLY ACCESSABLE TO ADMINISTRATOR
                auth: {
                    // BACK-END VERIFICATION FOR AN ADMINISTRATOR
                    roles:2,
                    // GUEST IS TRYING TO ACCESS THIS PAGE
                    redirect: 'questions/403',
                }
            },
        },

        // USER
        {
            path: '/users/password',
            name: 'users.password',
            component: UsersChangePassword,
            meta: {
                // ONLY ACCESSABLE IF LOGGED IN
                auth: true,
            },
        },
        {
            path: '/users',
            name: 'users.index',
            component: UsersIndex,
            meta: {
                auth: {
                    roles:2,
                    forbiddenRedirect: 'users/403',
                    redirect: 'menu'
                }
            }
        },

        // GAME
        {
            path: '/game',
            name: 'game',
            component: Game,
        },

        // HIGHSCORES
        {
            path: '/highscores',
            name: 'highscores',
            component: HighscoresIndex,
            meta:{
                auth: true
            }
        },

        // ERROR
        // 403 - FORBIDDEN ACCESS
        {
            // ANYTHING THAT ENDS WITH /403
            path:'*/403',
            name:'403',
            props: {
                statusCode: 403
            },
            component: ErrorPage

        },
        // 404 - NOT FOUND
        {
            // ANYTHING THAT ENDS WITH /404
            path: '*/404',
            name: '404',
            props: {
                statusCode: 404
            },
            component: ErrorPage
        },

        // HANDLES UMATCHED ROUTES
        {
            // REDIRECT TO 404
            path: '*',
            redirect: '*/404'
        },
    ],
});

// EXPORTS COMPONENT
export default router
