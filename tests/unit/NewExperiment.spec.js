import { shallowMount, createLocalVue } from '@vue/test-utils'
import VueRouter from 'vue-router'
import NewExperiment from '@/views/NewExperiment'
import { iotlab } from '@/rest'

Object.defineProperty(global, 'jQuery', {
  value: $,
})

global.jQuery = jest.fn()
global.$ = jest.fn()

const localVue = createLocalVue()
localVue.use(VueRouter)
const router = new VueRouter()

describe('NewExperiment.vue', () => {

  it('should match snapshot', () => {
    const wrapper = shallowMount(NewExperiment, {localVue, router})
    expect(wrapper.element).toBeDefined()
    expect(wrapper.element).toMatchSnapshot()
  })

})
