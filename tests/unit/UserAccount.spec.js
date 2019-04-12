import { shallowMount, createLocalVue } from '@vue/test-utils'
import VueRouter from 'vue-router'
import UserAccount from '@/views/UserAccount'
import { iotlab } from '@/rest'
jest.mock('@/rest')

const localVue = createLocalVue()
localVue.use(VueRouter)
const router = new VueRouter()

const errors = {
  has: jest.fn(),
  first: jest.fn(),
}

describe('UserAccount.vue', () => {

  it('should match snapshot', () => {
    const wrapper = shallowMount(UserAccount, {localVue, router, mocks: {errors}})
    expect(wrapper.element).toBeDefined()
    expect(wrapper.element).toMatchSnapshot()
  })

})
