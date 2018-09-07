import { shallowMount, createLocalVue } from '@vue/test-utils'
import VueRouter from 'vue-router'
import ExperimentList from '@/components/ExperimentList'
import { iotlab } from '@/rest'
jest.mock('@/rest')

// [{"submitted_duration":60,"submission_date":"2018-07-25T15:37:26Z","nb_nodes":15,"effective_duration":0,"name":"","stop_date":"1970-01-01T00:00:00Z","id":13166,"state":"Waiting","scheduled_date":"1970-01-01T00:00:00Z","user":"bformet","start_date":"1970-01-01T00:00:00Z"},{"submitted_duration":60,"submission_date":"2018-08-29T23:14:27Z","nb_nodes":10,"effective_duration":1,"name":"","stop_date":"1970-01-01T00:00:00Z","id":13226,"state":"Running","scheduled_date":"2018-08-29T23:14:29Z","user":"bformet","start_date":"2018-08-29T23:14:29Z"}]

const localVue = createLocalVue()
localVue.use(VueRouter)
const router = new VueRouter()

describe('ExperimentList.vue', () => {

  it('should match snapshot', () => {
    const wrapper = shallowMount(ExperimentList, {localVue, router})
    expect(wrapper.element).toBeDefined()
    expect(wrapper.element).toMatchSnapshot()
  })

})
