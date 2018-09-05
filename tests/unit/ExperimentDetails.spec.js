import { shallowMount, createLocalVue } from '@vue/test-utils'
import VueRouter from 'vue-router'
import Notifications from 'vue-notification'
import ExperimentDetails from '@/views/ExperimentDetails'
import { iotlab } from '@/rest'
import * as Filters from '@/filters'

jest.mock('@/rest')
iotlab.getExperiment = jest.fn().mockResolvedValue({"submitted_duration":20,"nb_nodes":2,"stop_date":"2017-01-16T14:22:41Z","type":"physical","firmwareassociations":[{"nodes":["m3-12.saclay.iot-lab.info","m3-9.saclay.iot-lab.info"],"firmwarename":"tutorial_m3.elf"}],"scheduled_date":"1970-01-01T00:00:00Z","submission_date":"2017-01-16T14:02:21Z","nodes":["m3-9.saclay.iot-lab.info","m3-12.saclay.iot-lab.info"],"effective_duration":20,"name":"test1","id":58099,"state":"Terminated","user":"formet","start_date":"2017-01-16T14:02:23Z"})
iotlab.getExperimentDeployment = jest.fn().mockResolvedValue({"0":["m3-9.saclay.iot-lab.info","m3-12.saclay.iot-lab.info"]})

const localVue = createLocalVue()
localVue.use(VueRouter)
localVue.use(Notifications)
const router = new VueRouter()

localVue.filter('formatDateTimeSec', Filters.formatDateTimeSec)
localVue.filter('humanizeDuration', Filters.humanDuration)
localVue.filter('stateBadgeClass', Filters.stateBadgeClass)

describe('ExperimentDetails.vue', () => {

  it('should match snapshot', async () => {
    const wrapper = await shallowMount(ExperimentDetails, {localVue, router, propsData: {
      id: 58099,
    }})
    await wrapper.vm.$nextTick() // wait for promise to resolve
    await wrapper.vm.$nextTick() // wait for promise to resolve
    await wrapper.vm.$nextTick() // wait for promise to resolve
    expect(wrapper.element).toBeDefined()
    expect(wrapper.element).toMatchSnapshot()
  })

})
