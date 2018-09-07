import { shallowMount, createLocalVue } from '@vue/test-utils'
import VueRouter from 'vue-router'
import SshKeysForm from '@/components/SshKeysForm'

const localVue = createLocalVue()
localVue.use(VueRouter)
const router = new VueRouter()

describe('SshKeysForm.vue', () => {

  it('should match snapshot', () => {
    const wrapper = shallowMount(SshKeysForm, {localVue, router, propsData: {
      keys: ['key1', 'key2']
    }})
    expect(wrapper.element).toBeDefined()
    expect(wrapper.element).toMatchSnapshot()
  })

  it('should be able to add key', () => {
    const wrapper = shallowMount(SshKeysForm, {localVue, router, propsData: {
      keys: ['key1', 'key2']
    }})
    expect(wrapper.vm.keys).toHaveLength(2)
    wrapper.find('li:last-child a').trigger('click')
    expect(wrapper.vm.keys).toHaveLength(3)
  })

  it('should be able to del key', () => {
    const wrapper = shallowMount(SshKeysForm, {localVue, router, propsData: {
      keys: ['key1', 'key2']
    }})
    expect(wrapper.vm.keys).toHaveLength(2)
    wrapper.find('li a.active a').trigger('click')
    expect(wrapper.vm.keys).toHaveLength(1)
  })

})
