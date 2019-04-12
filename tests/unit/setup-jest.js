import $ from 'jquery'
import moment from 'moment'
import localStorage from 'jest-localstorage-mock'

global.$ = global.jQuery = $
global.moment = moment
Object.defineProperty(window, 'localStorage', {
  value: localStorage,
})
