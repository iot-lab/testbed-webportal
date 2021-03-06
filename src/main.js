import 'bootstrap'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'font-awesome/css/font-awesome.min.css'
import '@/../public/font/OpenSans-Bold/stylesheet.css'

// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import VeeValidate from 'vee-validate'
import Notifications from 'vue-notification'
import velocity from 'velocity-animate'
import App from './App'
import router from './router'
import {iotlab} from './rest'
import './filters'
import './directives'

import { map3d } from '@/assets/map3d/map3d'

Vue.use(VeeValidate)
Vue.use(Notifications, {velocity})

Vue.config.productionTip = false

window.iotlab = iotlab
window.map3d = map3d

/* eslint-disable no-new */
new Vue({
  router,
  template: '<App/>',
  components: { App },
  render: h => h(App),
}).$mount('#app')
