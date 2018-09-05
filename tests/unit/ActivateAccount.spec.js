import { shallowMount, createLocalVue, RouterLinkStub } from '@vue/test-utils'
import Notifications from 'vue-notification'
import ActivateAccount from '@/views/ActivateAccount'
import { iotlab } from '@/rest'
jest.mock('@/rest')
iotlab.activateAccount = jest.fn()

const localVue = createLocalVue()
localVue.use(Notifications)

const $route = {
  query: {hash: 'toto'},
}

describe('ActivateAccount.vue', () => {

  it('should match snapshot', () => {
    const wrapper = shallowMount(ActivateAccount, {localVue, stubs: {RouterLink: RouterLinkStub}, mocks: {$route}})
    expect(wrapper.element).toBeDefined()
    expect(wrapper.element).toMatchSnapshot()
  })

  it('should activate account', async () => {
    const wrapper = shallowMount(ActivateAccount, {localVue, stubs: {RouterLink: RouterLinkStub}, mocks: {$route}})
    await wrapper.vm.$nextTick()
    expect(iotlab.activateAccount).toHaveBeenCalledWith(wrapper.vm.$route.query.hash)
  })

})
