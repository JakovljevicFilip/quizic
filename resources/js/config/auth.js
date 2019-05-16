import bearer from '@websanova/vue-auth/drivers/auth/bearer'
import VueResource from '@websanova/vue-auth/drivers/http/vue-resource.1.x.js'
import router from '@websanova/vue-auth/drivers/router/vue-router.2.x'

// SET TOKEN REFRESH TIME
// THIS FUNCTIONALITY DEALS WITH TIMESTAMPS AND SO IT NEEDS TO SEND A REQUEST SOME TIME BEFORE TOKEN BECOMES INVALID ON THE BACK-END
// MOST OPTIMAL SOLUTION I'VE FOUND IS ONE MINUTE BEFORE EXPIRING WITH POSSIBLE PROBLEMS IF ONE MINUTE IS A BACK-END VALUE
let refreshTime = process.env.MIX_JWT_TTL - 1;
// IF TOKEN IS SET TO EXPIRE IN 1 MINUTE
if(refreshTime < 1){
    // refreshTime DEFAULTS TO 1 MINUTE AS WELL
    refreshTime = 1;
}

// Auth base configuration some of this options
// can be override in method calls
const config = {
    auth: bearer,
    http: VueResource,
    router: router,
    tokenDefaultName: 'Authorization',
    tokenStore: ['localStorage'],
    rolesVar: 'role',
    registerData: {url: 'auth/register', method: 'POST', redirect: 'login'},
    loginData: {url: 'auth/login', method: 'POST', fetchUser: true},
    logoutData: {url: 'auth/logout', method: 'POST', redirect: 'login', makeRequest: true},
    fetchData: {url: 'auth/user', method: 'GET', enabled: true},
    refreshData: {url: 'auth/refresh', method: 'GET', enabled: true, interval: refreshTime},
}

export default config
