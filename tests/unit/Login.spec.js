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

  it('should render failed login form', () => {
    const wrapper = shallowMount(Login, {localVue, router})
    wrapper.setData({failed: true})
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
  })
  
})


/*
describe('HelloWorld.vue', () => {
  it('renders props.msg when passed', () => {
    const msg = 'new message'
    const wrapper = shallowMount(HelloWorld, {
      propsData: { msg },
    })
    expect(wrapper.text()).toMatch(msg)
  })
})

describe('MessageToggle.vue', () => {
  it('toggles msg passed to Message when button is clicked', () => {
    const wrapper = shallowMount(MessageToggle)
    const button = wrapper.find('#toggle-message')
    button.trigger('click')
    const MessageComponent = wrapper.find(Message)
    expect(MessageComponent.props()).toEqual({msg: 'message'})
    button.trigger('click')
    expect(MessageComponent.props()).toEqual({msg: 'toggled message'})
  })
})

describe('Message', () => {
  it('renders props.msg when passed', () => {
    const msg = 'new message'
    const wrapper = shallowMount(Message, {
      propsData: { msg }
    })
    expect(wrapper.text()).toBe(msg)
  })

  it('renders default message if not passed a prop', () => {
    const defaultMessage = 'default message'
    const wrapper = shallowMount(Message)
    expect(wrapper.text()).toBe(defaultMessage)
  })
})


describe('List.vue', () => {
  it('renders li for each item in props.items', () => {
    const items = ['1', '2']
    const wrapper = shallowMount(List, {
      propsData: { items }
    })
    expect(wrapper.findAll('li')).toHaveLength(items.length)
  })

  it('matches snapshot', () => {
    const items = ['item 1', 'item 2']
    const wrapper = shallowMount(List, {
      propsData: { items }
    })
    expect(wrapper.html()).toMatchSnapshot()
  })
})
*/