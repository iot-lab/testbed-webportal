// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import {auth} from './auth'

Vue.config.productionTip = false

router.beforeEach((to, from, next) => {
  if (!auth.loggedIn && to.meta.requiresAuth) {
    next('login')
  } else {
    next()
  }
})

/* eslint-disable no-new */
new Vue({
  router,
  template: '<App/>',
  components: { App },
}).$mount('#app')
