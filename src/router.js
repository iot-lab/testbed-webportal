import Vue from 'vue'
import Router from 'vue-router'
import Login from '@/views/Login'
import ResetPassword from '@/views/ResetPassword'
import Signup from '@/views/Signup'
import ActivateAccount from '@/views/ActivateAccount'
import UserAccount from '@/views/UserAccount'
import AdminGroups from '@/views/AdminGroups'
import AdminUsers from '@/views/AdminUsers'
import AdminAddUser from '@/views/AdminAddUser'
import AdminExperiments from '@/views/AdminExperiments'
import Resources from '@/views/Resources'
import Monitoring from '@/views/Monitoring'
import Experiment from '@/views/Experiment'
import ExperimentDetails from '@/views/ExperimentDetails'
import Dashboard from '@/views/Dashboard'
import Nodes from '@/views/Nodes'
import Drawgantt from '@/wp-menu/Drawgantt'
import {auth} from './auth'

Vue.use(Router)

const router = new Router({
  // base: process.env.NODE_ENV === 'production'
  //   ? process.env.VUE_APP_IOTLAB_PATH
  //   : '/',
  // mode: 'history',
  routes: [
    { path: '/', redirect: '/dashboard' },
    { path: '/drawgantt', name: 'drawgantt', component: Drawgantt },
    { path: '/signup', name: 'signup', component: Signup },
    { path: '/reset', name: 'reset', component: ResetPassword },
    { path: '/login', name: 'login', component: Login },
    { path: '/activate', name: 'activate', component: ActivateAccount },
    { path: '/dashboard', name: 'dashboard', component: Dashboard, meta: { requiresAuth: true } },
    { path: '/nodes', name: 'nodes', component: Nodes, meta: { requiresAuth: true } },
    { path: '/experiment', name: 'experiment', component: Experiment, meta: { requiresAuth: true } },
    { path: '/resources', name: 'resources', component: Resources, meta: { requiresAuth: true } },
    { path: '/resources/monitoring/new', name: 'newMonitoring', component: Monitoring, meta: { requiresAuth: true } },
    { path: '/resources/monitoring/:name/edit', name: 'monitoring', component: Monitoring, props: true, meta: { requiresAuth: true } },
    { path: '/account', name: 'account', component: UserAccount, meta: { requiresAuth: true } },
    { path: '/groups', name: 'groups', component: AdminGroups, meta: { requiresAdmin: true } },
    { path: '/users', name: 'users', component: AdminUsers, meta: { requiresAdmin: true } },
    { path: '/users/add', name: 'addUsers', component: AdminAddUser, meta: { requiresAdmin: true } },
    { path: '/users/:username/experiments', name: 'userExperiments', component: AdminExperiments, props: true, meta: { requiresAdmin: true } },
    { path: '/experiments', name: 'allExperiments', component: AdminExperiments, props: {username: ''}, meta: { requiresAdmin: true } },
    { path: '/experiments/:id', name: 'experimentDetails', component: ExperimentDetails, props: true, meta: { requiresAuth: true } },
  ],
})

router.beforeEach((to, from, next) => {
  // redirect to login page when appropriate
  if (to.meta.requiresAuth && !auth.loggedIn) {
    next({name: 'login', query: { next: to.path }})
  } else if (to.meta.requiresAdmin && !(auth.isAdmin && auth.loggedIn)) {
    next({name: 'login', query: { next: to.path }})
  } else {
    next()
  }
})

export default router
