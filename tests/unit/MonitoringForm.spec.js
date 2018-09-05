import { shallowMount, createLocalVue } from '@vue/test-utils'
import VueRouter from 'vue-router'
import Notifications from 'vue-notification'
import MonitoringForm from '@/components/MonitoringForm'
import { iotlab } from '@/rest'
jest.mock('@/rest')
iotlab.updateMonitoringProfile = jest.fn()
iotlab.createMonitoringProfile = jest.fn()

const localVue = createLocalVue()
const router = new VueRouter()
localVue.use(VueRouter)
localVue.use(Notifications)
const profiles = [{"profilename":"","consumption":{"average":4,"period":8244,"current":true,"power":false,"voltage":false},"power":"dc","nodearch":"a8"},{"profilename":"conso","consumption":{"average":4,"period":8244,"current":true,"power":true,"voltage":true},"power":"dc","nodearch":"m3"},{"profilename":"radio","power":"dc","nodearch":"custom","radio":{"mode":"rssi","period":1000,"channels":[11,13],"num_per_channel":16}}]

describe('MonitoringForm.vue', () => {

  it('should match snapshot', () => {
    const wrapper = shallowMount(MonitoringForm, {localVue, router, propsData: {
      monitoringProfile: profiles[0]
    }})
    expect(wrapper.element).toBeDefined()
    expect(wrapper.element).toMatchSnapshot()
  })

  it('should match snapshot 2', () => {
    const wrapper = shallowMount(MonitoringForm, {localVue, router, propsData: {
      monitoringProfile: profiles[1]
    }})
    expect(wrapper.element).toBeDefined()
    expect(wrapper.element).toMatchSnapshot()
  })

  it('should match snapshot 3', () => {
    const wrapper = shallowMount(MonitoringForm, {localVue, router, propsData: {
      monitoringProfile: profiles[2]
    }})
    expect(wrapper.element).toBeDefined()
    expect(wrapper.element).toMatchSnapshot()
  })

  it('should update profile and redirect', () => {
    const wrapper = shallowMount(MonitoringForm, {localVue, router, propsData: {
      monitoringProfile: profiles[1]
    }})
    // change monitoring profile for watch() to take effect
    wrapper.setProps({
      monitoringProfile: profiles[2]
    })

    wrapper.find('form').trigger('submit')
    expect(iotlab.updateMonitoringProfile).toHaveBeenCalled()
    
    wrapper.vm.$router.push = jest.fn()
    wrapper.vm.$nextTick(() => {
      expect(wrapper.vm.$router.push).toHaveBeenCalledWith({name: 'resources'})
    })
  })

})
