import { shallowMount, createLocalVue } from '@vue/test-utils'
import VueRouter from 'vue-router'
import Notifications from 'vue-notification'
import Dashboard from '@/views/Dashboard'
import { iotlab } from '@/rest'

jest.mock('@/rest')
iotlab.getSites = jest.fn().mockResolvedValue([{"site":"strasbourg"},{"site":"paris"},{"site":"grenoble"},{"site":"lille"},{"site":"saclay"},{"site":"lyon"}])
iotlab.getNodes = jest.fn().mockResolvedValue([])
iotlab.getRunningExperiments = jest.fn().mockResolvedValue([{"submitted_duration":60,"submission_date":"2018-08-29T23:14:27Z","nodes":["a8-1.devgrenoble.iot-lab.info","a8-4.devgrenoble.iot-lab.info","a8-17.devgrenoble.iot-lab.info","a8-3.devgrenoble.iot-lab.info","a8-5.devgrenoble.iot-lab.info","a8-7.devgrenoble.iot-lab.info","a8-9.devgrenoble.iot-lab.info","a8-6.devgrenoble.iot-lab.info","a8-2.devgrenoble.iot-lab.info","a8-8.devgrenoble.iot-lab.info"],"nb_nodes":10,"effective_duration":0,"name":"","stop_date":"1970-01-01T00:00:00Z","id":13226,"state":"Launching","scheduled_date":"2018-08-29T23:14:29Z","user":"bformet","start_date":"2018-08-29T23:14:29Z"}])
iotlab.getUserExperimentsCount = jest.fn().mockResolvedValue({"running":0,"terminated":9,"upcoming":0})

const localVue = createLocalVue()
localVue.use(VueRouter)
localVue.use(Notifications)
const router = new VueRouter()

describe('Dashboard.vue', () => {

  it('should match snapshot', () => {
    const wrapper = shallowMount(Dashboard, {localVue, router})
    expect(wrapper.element).toBeDefined()
    expect(wrapper.element).toMatchSnapshot()
  })

})
