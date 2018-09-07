import { shallowMount, createLocalVue } from '@vue/test-utils'
import VueRouter from 'vue-router'
import Notifications from 'vue-notification'
import ResetPassword from '@/views/ResetPassword'
import { iotlab } from '@/rest'
jest.mock('@/rest')
iotlab.resetPassword = jest.fn()

const localVue = createLocalVue()
localVue.use(VueRouter)
localVue.use(Notifications)
const router = new VueRouter()

describe('ResetPassword.vue', () => {

  it('should match snapshot', () => {
    const wrapper = shallowMount(ResetPassword, {localVue, router})
    expect(wrapper.element).toBeDefined()
    expect(wrapper.element).toMatchSnapshot()
  })

  it('should send recovery email', async () => {
    const wrapper = shallowMount(ResetPassword, {localVue, router})
    const email = 'a@a.com'

    // fill form
    wrapper.find('input[type="email"]').setValue(email)

    // submit form
    wrapper.find('form').trigger('submit')
    expect(iotlab.resetPassword).toHaveBeenCalledWith(email)
  })

})
