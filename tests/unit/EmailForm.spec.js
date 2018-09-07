import { shallowMount, createLocalVue } from '@vue/test-utils'
import VueRouter from 'vue-router'
import VeeValidate from 'vee-validate'
import EmailForm from '@/components/EmailForm'
import { iotlab } from '@/rest'
jest.mock('@/rest')

const router = new VueRouter()
const localVue = createLocalVue()
localVue.use(VueRouter)
// Cannot test using VeeValidate due to a bug, should try different lib versions or wait for a fix
// https://github.com/vuejs/vue-test-utils/issues/829
//
// workaround:
// localVue.use(VeeValidate)
const errors = {
  has: jest.fn(),
  first: jest.fn(),
}

describe('EmailForm.vue', () => {

  it('should match snapshot', () => {
    const wrapper = shallowMount(EmailForm, {localVue, router, mocks: {errors}})
    expect(wrapper.element).toBeDefined()
    expect(wrapper.element).toMatchSnapshot()
  })

  it('should match snapshot with props', () => {
    const wrapper = shallowMount(EmailForm, {localVue, router, mocks: {errors}, propsData: {
      to: 'a@a.com, admin@iotlab.info',
      subject: 'subject',
      message: 'message',
    }})
    expect(wrapper.element).toBeDefined()
    expect(wrapper.element).toMatchSnapshot()
  })

  // it('should send email', () => {
  //   const wrapper = shallowMount(EmailForm, {localVue, router, propsData: {
  //     to: 'a@a.com',
  //     subject: 'subject',
  //     message: 'message',
  //   }})
  // })

})
