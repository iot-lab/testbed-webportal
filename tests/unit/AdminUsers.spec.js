import { shallowMount, createLocalVue } from '@vue/test-utils'
import VueRouter from 'vue-router'
import AdminUsers from '@/views/AdminUsers'
import { iotlab } from '@/rest'
jest.mock('@/rest')

const localVue = createLocalVue()
localVue.use(VueRouter)
const router = new VueRouter()

describe('AdminUsers.vue', () => {

  it('should match snapshot', () => {
    const wrapper = shallowMount(AdminUsers, {localVue, router})
    expect(wrapper.element).toBeDefined()
    expect(wrapper.element).toMatchSnapshot()
  })

})
