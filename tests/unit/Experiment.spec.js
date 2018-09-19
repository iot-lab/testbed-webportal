import { shallowMount, createLocalVue } from '@vue/test-utils'
import VueRouter from 'vue-router'
import Experiment from '@/views/Experiment'
import { iotlab } from '@/rest'

Object.defineProperty(global, 'jQuery', {
  value: $,
})

global.jQuery = jest.fn()
global.$ = jest.fn()

const localVue = createLocalVue()
localVue.use(VueRouter)
const router = new VueRouter()

describe('Experiment.vue', () => {

  it('should match snapshot', () => {
    const wrapper = shallowMount(Experiment, {localVue, router})
    expect(wrapper.element).toBeDefined()
    expect(wrapper.element).toMatchSnapshot()
  })

})
