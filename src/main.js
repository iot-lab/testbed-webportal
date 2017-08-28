// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import Notifications from 'vue-notification'
import velocity from 'velocity-animate'
import moment from 'moment'
import App from './App'
import router from './router'

Vue.use(Notifications, {velocity})

Vue.config.productionTip = false

Vue.filter('formatDate', function (value) {
  if (value) {
    var m = /^(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})Z$/g.exec(value)
    value = (m) ? `${m[1]}-${m[2]}-${m[3]}T${m[4]}:${m[5]}:${m[6]}Z` : value
    return moment(String(value)).format('YYYY-MM-DD')
  }
})

Vue.filter('formatDateTime', function (value) {
  if (value) {
    var m = /^(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})Z$/g.exec(value)
    value = (m) ? `${m[1]}-${m[2]}-${m[3]}T${m[4]}:${m[5]}:${m[6]}Z` : value
    return moment(String(value)).format('YYYY-MM-DD HH:mm')
  }
})

/* eslint-disable no-new */
new Vue({
  router,
  template: '<App/>',
  components: { App },
}).$mount('#app')
