import { shallowMount, createLocalVue } from '@vue/test-utils'
import VueRouter from 'vue-router'
import NewExperiment from '@/views/NewExperiment'
import { iotlab } from '@/rest'
// import '@/directives'

// import $ from 'jquery'

const router = new VueRouter()
const localVue = createLocalVue()
localVue.use(VueRouter)
// localVue.directive('tooltip', function (el, binding) {
//   if (binding.oldValue === binding.value) return
//   $(el).tooltip('dispose')
//   $(el).tooltip({
//     title: binding.value,
//     placement: binding.arg,
//     trigger: 'hover',
//     html: binding.modifiers.html || false,
//     delay: 400,
//   })
// })

describe('NewExperiment.vue', () => {

  it('should match snapshot', () => {
    const wrapper = shallowMount(NewExperiment, {localVue, router})
    expect(wrapper.element).toBeDefined()
    expect(wrapper.element).toMatchSnapshot()
  })

})
