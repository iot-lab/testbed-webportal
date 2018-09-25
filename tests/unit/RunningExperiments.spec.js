import { shallowMount, createLocalVue } from '@vue/test-utils'
import VueRouter from 'vue-router'
import RunningExperiments from '@/components/RunningExperiments'
import * as Filters from '@/filters'
import { iotlab } from '@/rest'
jest.mock('@/rest')

const router = new VueRouter()
const localVue = createLocalVue()
localVue.use(VueRouter)
localVue.filter('humanizeDuration', Filters.humanDuration)

describe('RunningExperiments.vue', () => {

  it('should match snapshot', () => {
    const propsData = {
      expList: [{"submitted_duration":20,"submission_date":"2018-09-25T15:02:57Z","nodes":["m3-12.devgrenoble.iot-lab.info","m3-10.devgrenoble.iot-lab.info","m3-13.devgrenoble.iot-lab.info"],"nb_nodes":3,"effective_duration":0,"name":"","stop_date":"1970-01-01T00:00:00Z","id":13308,"state":"Running","scheduled_date":"2018-09-25T15:02:58Z","user":"bformet","start_date":"2018-09-25T15:02:58Z"},{"submitted_duration":60,"submission_date":"2018-09-25T15:03:03Z","nodes":["a8-1.devgrenoble.iot-lab.info","a8-4.devgrenoble.iot-lab.info","a8-17.devgrenoble.iot-lab.info","a8-3.devgrenoble.iot-lab.info","a8-5.devgrenoble.iot-lab.info","a8-7.devgrenoble.iot-lab.info","a8-9.devgrenoble.iot-lab.info","a8-6.devgrenoble.iot-lab.info","a8-2.devgrenoble.iot-lab.info","a8-8.devgrenoble.iot-lab.info"],"nb_nodes":10,"effective_duration":0,"name":"","stop_date":"1970-01-01T00:00:00Z","id":13309,"state":"Running","scheduled_date":"2018-09-25T15:03:04Z","user":"bformet","start_date":"2018-09-25T15:03:04Z"}],
    }
    const wrapper = shallowMount(RunningExperiments, {localVue, router, propsData})
    expect(wrapper.element).toBeDefined()
    expect(wrapper.element).toMatchSnapshot()
  })

})
