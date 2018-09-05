import { shallowMount, createLocalVue } from '@vue/test-utils'
import VueRouter from 'vue-router'
import ModalDialog from '@/components/ModalDialog'
import { iotlab } from '@/rest'
jest.mock('@/rest')

const localVue = createLocalVue()
localVue.use(VueRouter)
const router = new VueRouter()

describe('ModalDialog.vue', () => {

  it('should match snapshot', () => {
    const wrapper = shallowMount(ModalDialog, {localVue, router})
    expect(wrapper.element).toBeDefined()
    expect(wrapper.element).toMatchSnapshot()
  })

})
