import Vue from 'vue'
import $ from 'jquery'

Vue.directive('tooltip', function (el, binding) {
  if (binding.oldValue === binding.value) return
  $(el).tooltip('dispose')
  $(el).tooltip({
    title: binding.value,
    placement: binding.arg,
    trigger: 'hover',
    delay: 400,
  })
})
