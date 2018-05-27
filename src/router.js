import Vue from 'vue'
import Router from 'vue-router'
import Login from '@/components/Login'
import ResetPassword from '@/components/ResetPassword'
import Signup from '@/components/Signup'
import ActivateAccount from '@/components/ActivateAccount'
import UserAccount from '@/components/UserAccount'
import AdminUsers from '@/components/AdminUsers'
import AdminAddUser from '@/components/AdminAddUser'
import AdminExperiments from '@/components/AdminExperiments'
import Resources from '@/components/Resources'
import Monitoring from '@/components/Monitoring'
import Experiment from '@/components/Experiment'
import ExperimentDetails from '@/components/ExperimentDetails'
import Dashboard from '@/components/Dashboard'
import Nodes from '@/components/Nodes'
import Map from '@/components/Map'
import {auth} from './auth'

Vue.use(Router)

const router = new Router({
  routes: [
    { path: '/', redirect: '/dashboard' },
    { path: '/map', name: 'map', component: Map },
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
