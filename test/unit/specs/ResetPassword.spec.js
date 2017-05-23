import Vue from 'vue'
import ResetPassword from '@/components/ResetPassword'

describe('ResetPassword.vue', () => {
  it('should render ResetPassword form', () => {
    const Constructor = Vue.extend(ResetPassword)
    const vm = new Constructor().$mount()
    expect(vm.$el.querySelector('form button').textContent)
      .to.equal('Reset password')
  })
})
