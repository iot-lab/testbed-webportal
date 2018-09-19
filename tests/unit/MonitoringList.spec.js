import { shallowMount, createLocalVue } from '@vue/test-utils'
import VueRouter from 'vue-router'
import Notifications from 'vue-notification'
import MonitoringList from '@/components/MonitoringList'
import { iotlab } from '@/rest'

jest.mock('@/rest')
iotlab.getMonitoringProfiles = jest.fn().mockResolvedValue([{"profilename":"a8prof","consumption":{"average":4,"period":8244,"current":true,"power":false,"voltage":false},"power":"dc","nodearch":"a8"},{"profilename":"conso","consumption":{"average":4,"period":8244,"current":true,"power":true,"voltage":true},"power":"dc","nodearch":"m3"},{"profilename":"radio","power":"dc","nodearch":"custom","radio":{"mode":"rssi","period":1000,"channels":[11,13],"num_per_channel":16}}])

const router = new VueRouter()
const localVue = createLocalVue()
localVue.use(VueRouter)
localVue.use(Notifications)

describe('MonitoringList.vue', () => {

  it('should match snapshot', async () => {
    const wrapper = await shallowMount(MonitoringList, {localVue, router})
    await wrapper.vm.$nextTick() // wait for promise to resolve
    expect(wrapper.element).toBeDefined()
    expect(wrapper.element).toMatchSnapshot()
  })

})
