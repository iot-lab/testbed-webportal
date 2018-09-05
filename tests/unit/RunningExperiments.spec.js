import { shallowMount, createLocalVue } from '@vue/test-utils'
import VueRouter from 'vue-router'
import RunningExperiments from '@/views/RunningExperiments'
import { iotlab } from '@/rest'
jest.mock('@/rest')

const localVue = createLocalVue()
localVue.use(VueRouter)
const router = new VueRouter()

describe('RunningExperiments.vue', () => {

  it('should match snapshot', () => {
    const wrapper = shallowMount(RunningExperiments, {localVue, router})
    expect(wrapper.element).toBeDefined()
    expect(wrapper.element).toMatchSnapshot()
  })

})
