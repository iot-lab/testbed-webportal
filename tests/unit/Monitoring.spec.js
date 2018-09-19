import { shallowMount, createLocalVue } from '@vue/test-utils'
import VueRouter from 'vue-router'
import Monitoring from '@/views/Monitoring'
import { iotlab } from '@/rest'
jest.mock('@/rest')

const localVue = createLocalVue()
localVue.use(VueRouter)
const router = new VueRouter()

describe('Monitoring.vue', () => {

  it('should match snapshot', () => {
    const wrapper = shallowMount(Monitoring, {localVue, router})
    expect(wrapper.element).toBeDefined()
    expect(wrapper.element).toMatchSnapshot()
  })

})
