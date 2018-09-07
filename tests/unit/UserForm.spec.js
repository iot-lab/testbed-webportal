import { shallowMount, createLocalVue } from '@vue/test-utils'
import VueRouter from 'vue-router'
import UserForm from '@/components/UserForm'
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

describe('UserForm.vue', () => {

  it('should match snapshot', () => {
    const wrapper = shallowMount(UserForm, {localVue, router, mocks: {errors}, propsData: {
      user: {"firstName":"Benoit","lastName":"Formet","email":"benoit.formet@inria.fr","organization":"Inria Saclay","city":"Paris","country":"France","login":"formet","motivations":"My goal is to make the iot-lab / testbed website shine","status":"active","created":"2016-12-16T09:49:09Z","category":"Academic","groups":["admin"],"sshkeys":["toto"]},
    }})
    expect(wrapper.element).toBeDefined()
    expect(wrapper.element).toMatchSnapshot()
  })

  it('should match snapshot 2', () => {
    const wrapper = shallowMount(UserForm, {localVue, router, mocks: {errors}, propsData: {
      user: {"firstName":"Benoit","lastName":"Formet","email":"benoit.formet@inria.fr","organization":"Inria Saclay","city":"Paris","country":"France","login":"formet","motivations":"My goal is to make the iot-lab / testbed website shine","status":"active","created":"2016-12-16T09:49:09Z","category":"Academic","groups":["admin"],"sshkeys":["toto"]},
      hidden: ['firstName','lastName','email'],
      admin: true,
      mode: 'edit',
    }})
    expect(wrapper.element).toBeDefined()
    expect(wrapper.element).toMatchSnapshot()
  })

})
