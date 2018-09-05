import { shallowMount, createLocalVue } from '@vue/test-utils'
import VueRouter from 'vue-router'
import AdminExperiments from '@/views/AdminExperiments'
import { iotlab } from '@/rest'
jest.mock('@/rest')

const localVue = createLocalVue()
localVue.use(VueRouter)
const router = new VueRouter()

describe('AdminExperiments.vue', () => {

  it('should match snapshot', () => {
    const wrapper = shallowMount(AdminExperiments, {localVue, router})
    expect(wrapper.element).toBeDefined()
    expect(wrapper.element).toMatchSnapshot()
  })

})
