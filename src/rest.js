import axios from 'axios'

// const API_v1_URL = 'http://localhost:6060/devwww.iot-lab.info:443/rest/'
// const API_v1_URL = 'http://localhost:6060/devwww.iot-lab.info:443/api/'
const API_V1_URL = 'https://devwww.iot-lab.info/rest/'
const API_V2_URL = 'https://devwww.iot-lab.info/api/'

// export const iotlab = new function () {

//   this.apiv1 = axios.create({baseURL: API_V1_URL, auth: JSON.parse(sessionStorage.getItem('apiAuth') || '{}')})
//   this.apiv2 = axios.create({baseURL: API_V2_URL, auth: JSON.parse(sessionStorage.getItem('apiAuth') || '{}')})
//   this.api = this.apiv2

export const iotlab = {

  apiv1: axios.create({baseURL: API_V1_URL, auth: JSON.parse(sessionStorage.getItem('apiAuth') || '{}')}),
  apiv2: axios.create({baseURL: API_V2_URL, auth: JSON.parse(sessionStorage.getItem('apiAuth') || '{}')}),
  api: axios.create({baseURL: API_V2_URL, auth: JSON.parse(sessionStorage.getItem('apiAuth') || '{}')}),

  create (username, password) {
    this.apiv2 = axios.create({
      baseURL: API_V2_URL,
      auth: {
        username: username,
        password: password,
      },
    })
    this.apiv1 = axios.create({
      baseURL: API_V1_URL,
      auth: {
        username: username,
        password: password,
      },
    })
    this.api = this.apiv2
  },

  async signup (user) {
    await iotlab.api.post('/users', user)
  },

  async resetPassword (email) {
    await iotlab.api.post(`/users/reset_password`, {'email': email})
  },

  async changePassword (oldPwd, newPwd, confirmPwd) {
    await iotlab.api.put('/user/password', {
      'old_password': oldPwd,
      'new_password': newPwd,
      'confirm_new_password': confirmPwd,
    })
  },

  async getSSHkeys () {
    return await iotlab.api.get('/user/keys').then(resp => resp.data.sshkeys)
  },

  async modifySSHkeys (keys) {
    return await iotlab.api.post('/user/keys', {'sshkeys': keys})
  },

  async getUserInfo () {
    return await iotlab.api.get('/user').then(resp => resp.data)
  },

  async setUserInfo (user) {
    return await iotlab.api.put('/user', user)
  },

  async getUserExperiments () {
    return await iotlab.apiv1.get('/experiments?total').then(resp => resp.data)
  },

  async getStats () {
    return await iotlab.apiv1.get('/stats').then(resp => resp.data)
  },

  async getSites () {
    return await iotlab.apiv1.get('/experiments?sites').then(resp => resp.data.items)
  },

  async getSiteResources () {
    return await iotlab.apiv1.get('/experiments?resources').then(resp => resp.data.items)
  },

}
