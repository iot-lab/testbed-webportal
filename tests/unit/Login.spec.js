import { shallowMount, createLocalVue } from '@vue/test-utils'
import VueRouter from 'vue-router'
import Login from '@/views/Login'
import { auth } from '@/auth'
jest.mock('@/auth')

const localVue = createLocalVue()
localVue.use(VueRouter)
const router = new VueRouter()

describe('Login.vue', () => {

  it('should render blank login form', () => {
    const wrapper = shallowMount(Login, {localVue, router})
    expect(wrapper.element).toBeDefined()
    expect(wrapper.element).toMatchSnapshot()
  })

  it('should redirect to dashboard after login', async () => {
    const wrapper = shallowMount(Login, {localVue, router})
    const login = 'login'
    const pwd = 'password'

    // fill form
    wrapper.find('input[type="text"]').setValue(login)
    wrapper.find('input[type="password"]').setValue(pwd)
    expect(wrapper.vm.username).toBe(login)
    expect(wrapper.vm.password).toBe(pwd)
    
    // submit form
    wrapper.find('form').trigger('submit')
    expect(auth.doLogin).toHaveBeenCalledWith(login, pwd)
    
    wrapper.vm.$router.push = jest.fn()
    wrapper.vm.$nextTick(() => {
      expect(wrapper.vm.$router.push).toHaveBeenCalledWith('dashboard')
    })

  })

  it('should have failed state after bad login', async () => {
    const wrapper = shallowMount(Login, {localVue, router})

    auth.doLogin.mockImplementation(() => {
      throw new Error()
    })

    wrapper.setData({failed: false})
    wrapper.find('form').trigger('submit')
    expect(auth.doLogin).toHaveBeenCalledWith('', '')
    expect(wrapper.vm.failed).toBe(true)
    expect(wrapper.element).toBeDefined()
    expect(wrapper.element).toMatchSnapshot()
  })
  
})
