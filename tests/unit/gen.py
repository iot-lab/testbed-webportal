
l = ['EmailForm',
'ExperimentList',
'FilterSelect',
'Map3d',
'ModalDialog',
'MonitoringForm',
'MonitoringList',
'SshKeysForm',
'UserForm',
'ActivateAccount',
'AdminAddUser',
'AdminExperiments',
'AdminGroups',
'AdminUsers',
'Dashboard',
'Experiment',
'ExperimentDetails',
'Monitoring',
'Nodes',
'Resources',
'RunningExperiments',
'Signup',
'UserAccount',]

c = 'components'

for f in l:
    if f == 'ActivateAccount':
        c = 'views'
    with open("%s.spec.js" % f, "w") as text_file:
        text_file.write("""import { shallowMount, createLocalVue } from '@vue/test-utils'
import VueRouter from 'vue-router'
import %s from '@/%s/%s'
import { iotlab } from '@/rest'
jest.mock('@/rest')

const localVue = createLocalVue()
localVue.use(VueRouter)
const router = new VueRouter()

describe('%s.vue', () => {

  it('should match snapshot', () => {
    const wrapper = shallowMount(%s, {localVue, router})
    expect(wrapper.element).toBeDefined()
    expect(wrapper.element).toMatchSnapshot()
  })

})
""" % (f, c, f, f, f))