import bearer from '@websanova/vue-auth/drivers/auth/bearer'
import axios from '@websanova/vue-auth/drivers/http/axios.1.x'
import router from '@websanova/vue-auth/drivers/router/vue-router.2.x'

// Auth base configuration some of this options
// can be override in method calls
const config = {
  auth: bearer,
  http: axios,
  router: router,
  tokenDefaultName: 'Authorization',
  tokenStore: ['localStorage'],
  rolesVar: 'role',
  registerData: {url: 'auth/register', method: 'POST', redirect: 'login'},
  loginData: {url: 'auth/login', method: 'POST', fetchUser: true},
  logoutData: {url: 'auth/logout', method: 'POST', redirect: 'login', makeRequest: true},
  fetchData: {url: 'auth/user', method: 'GET', enabled: true},
  // I AM HANDLING REFRESH FUNCTIONALITY MYSELF INSIDE app.js FILE
  refreshData: {url: 'auth/refresh', method: 'GET', enabled: false, interval: 30}
}

export default config
