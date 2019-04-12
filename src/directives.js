import Vue from 'vue'
import $ from 'jquery'

Vue.directive('tooltip', function (el, binding) {
  if (binding.oldValue === binding.value) return
  $(el).tooltip('dispose')
  $(el).tooltip({
    title: binding.value,
    placement: binding.arg,
    trigger: 'hover',
    html: binding.modifiers.html || false,
    delay: 400,
  })
})

// advanced-tooltip, option object directly passed to jQuery tooltip
Vue.directive('adv-tooltip', function (el, binding) {
  if (binding.oldValue === binding.value) return
  $(el).tooltip('dispose')
  $(el).tooltip(binding.value)
})
