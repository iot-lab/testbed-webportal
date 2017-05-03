// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import VueResource from 'vue-resource'
import App from './App'
import router from './router'

Vue.use(VueResource)

Vue.config.productionTip = false

let loggedIn = false

function doLogin () {
  loggedIn = true
}
function doLogout () {
  loggedIn = false
}

router.beforeEach((to, from, next) => {
  if (!loggedIn && to.meta.requiresAuth) {
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

export {doLogin, doLogout}
