import Vue from 'vue'
import Router from 'vue-router'
import Page404 from '@/404'
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
import MonitoringList from '@/components/MonitoringList'
import Firmware from '@/views/Firmware'
import FirmwareList from '@/components/FirmwareList'
import NewExperiment from '@/views/NewExperiment'
import ExperimentDetails from '@/views/ExperimentDetails'
import Dashboard from '@/views/Dashboard'
import Status from '@/views/Status'
import {auth} from './auth'

Vue.use(Router)

const router = new Router({
  base: process.env.NODE_ENV === 'production'
    ? process.env.VUE_APP_IOTLAB_PATH
    : '/',
  mode: 'history',
  routes: [
    { path: '/', redirect: '/dashboard' },
    { path: '/drawgantt', name: 'drawgantt', component: Status, props: {showData: 'activity'} },
    { path: '/signup', name: 'signup', component: Signup },
    { path: '/reset', name: 'reset', component: ResetPassword },
    { path: '/login', name: 'login', component: Login },
    { path: '/activate', name: 'activate', component: ActivateAccount },
    { path: '/dashboard', name: 'dashboard', component: Dashboard, meta: { requiresAuth: true } },
    { path: '/status', name: 'status', component: Status, meta: { requiresAuth: true } },
    { path: '/status/activity', name: 'activity', component: Status, props: {showData: 'activity'} },
    { path: '/experiment', name: 'experiment', component: NewExperiment, meta: { requiresAuth: true } },
    { path: '/resources',
      name: 'resources',
      redirect: { name: 'listMonitoring' },
      component: Resources,
      meta: { requiresAuth: true },
      children: [
        { path: 'monitoring', name: 'listMonitoring', component: MonitoringList, meta: { requiresAuth: true } },
        { path: 'monitoring_new', name: 'newMonitoring', component: Monitoring, meta: { requiresAuth: true } },
        { path: 'monitoring/:name', name: 'monitoring', component: Monitoring, props: true, meta: { requiresAuth: true } },
        { path: 'firmware', name: 'listFirmware', component: FirmwareList, meta: { requiresAuth: true } },
        { path: 'firmware_new', name: 'newFirmware', component: Firmware, meta: { requiresAuth: true } },
        { path: 'firmware/:name', name: 'firmware', component: Firmware, props: true, meta: { requiresAuth: true } },
      ],
    },
    { path: '/account', name: 'account', component: UserAccount, meta: { requiresAuth: true } },
    { path: '/groups', name: 'groups', component: AdminGroups, meta: { requiresAdmin: true } },
    { path: '/users', name: 'users', component: AdminUsers, meta: { requiresAdmin: true } },
    { path: '/users/add', name: 'addUsers', component: AdminAddUser, meta: { requiresAdmin: true } },
    { path: '/users/:username/experiments', name: 'userExperiments', component: AdminExperiments, props: true, meta: { requiresAdmin: true } },
    { path: '/experiments', name: 'allExperiments', component: AdminExperiments, props: {username: ''}, meta: { requiresAdmin: true } },
    { path: '/experiments/:id', name: 'experimentDetails', component: ExperimentDetails, props: true, meta: { requiresAuth: true } },
    { path: '*', component: Page404 },
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
