import { shallowMount, createLocalVue } from '@vue/test-utils'
import FilterSelect from '@/components/FilterSelect'
import { iotlab } from '@/rest'
jest.mock('@/rest')

describe('FilterSelect.vue', () => {

  it('should match snapshot style 1', () => {
    const wrapper = shallowMount(FilterSelect, {propsData: {
      title: 'title',
      items: ['item1', 'item2'],
    }})
    expect(wrapper.element).toBeDefined()
    expect(wrapper.element).toMatchSnapshot()
  })

  it('should match snapshot style 2', () => {
    const wrapper = shallowMount(FilterSelect, {propsData: {
      all: 'all',
      selected: 'value1',
      items: [
        {
          option: 'option1',
          value: 'value1',
        },
        {
          option: 'option2',
          value: 'value2',
        }
      ],
    }})
    expect(wrapper.element).toBeDefined()
    expect(wrapper.element).toMatchSnapshot()
  })

  it('should emit input event on change', () => {
    const wrapper = shallowMount(FilterSelect, {propsData: {
      all: 'all',
      items: ['item1', 'item2'],
    }})
    wrapper.find('select').trigger('change')
    expect(wrapper.emitted().input.length).toBe(1)
  })

})
