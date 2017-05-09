import Vue from 'vue'
import Router from 'vue-router'
import Hello from '@/components/Hello'
import Login from '@/components/Login'
import ResetPassword from '@/components/ResetPassword'
import Signup from '@/components/Signup'
import UserAccount from '@/components/UserAccount'
import Monitor from '@/components/Monitor'
import Experiment from '@/components/Experiment'
import Dashboard from '@/components/Dashboard'

Vue.use(Router)

export default new Router({
  routes: [
    { path: '/', redirect: '/dashboard' },
    { path: '/dashboard', name: 'dashboard', component: Dashboard, meta: { requiresAuth: true } },
    { path: '/experiment', name: 'experiment', component: Experiment, meta: { requiresAuth: true } },
    { path: '/monitor', name: 'monitor', component: Monitor, meta: { requiresAuth: true } },
    { path: '/UserAccount', name: 'UserAccount', component: UserAccount, meta: { requiresAuth: true } },
    { path: '/signup', name: 'signup', component: Signup },
    { path: '/reset', name: 'reset', component: ResetPassword },
    { path: '/login', name: 'login', component: Login },
    { path: '/hello', name: 'hello', component: Hello },
  ],
})
