import { shallowMount, createLocalVue } from '@vue/test-utils'
import VueRouter from 'vue-router'
import AdminAddUser from '@/views/AdminAddUser'
import { iotlab } from '@/rest'
jest.mock('@/rest')

// Cannot test using VeeValidate due to a bug, should try different lib versions or wait for a fix
// https://github.com/vuejs/vue-test-utils/issues/829
//
// workaround:
// localVue.use(VeeValidate)
const errors = {
  has: jest.fn(),
  first: jest.fn(),
}

const localVue = createLocalVue()
localVue.use(VueRouter)
const router = new VueRouter()

describe('AdminAddUser.vue', () => {

  it('should match snapshot', () => {
    const wrapper = shallowMount(AdminAddUser, {localVue, router, mocks: {errors}})
    expect(wrapper.element).toBeDefined()
    expect(wrapper.element).toMatchSnapshot()
  })

})
