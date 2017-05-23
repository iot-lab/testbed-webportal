import Vue from 'vue'
import Login from '@/components/Login'

describe('Login.vue', () => {
  it('should render login form', () => {
    const Constructor = Vue.extend(Login)
    const vm = new Constructor().$mount()
    expect(vm.$el.querySelector('form button').textContent)
      .to.equal('Log in')
  })
})
