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

  // USERS API

  async signup (user) {
    await iotlab.api.post('/users', user)
  },

  async deleteUser (login) {
    await iotlab.api.delete(`/users/${login}`)
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

  async deleteSSHkey (id) {
    return await iotlab.api.delete(`/user/keys/${id}`)
  },

  async getUsers ({status, isAdmin, search} = {}) {
    let params = {}
    if (status) {
      params.status = status
    }
    if (isAdmin) {
      params.isadmin = isAdmin
      params.status = 'active'
    }
    if (search) {
      params.search = search
    }
    return await iotlab.api.get('/users', {params: params}).then(resp => resp.data)
  },

  async getUserInfo (login = undefined) {
    if (login) {
      return await iotlab.api.get(`/users/${login}`).then(resp => resp.data)
    } else {
      return await iotlab.api.get('/user').then(resp => resp.data)
    }
  },

  async setUserInfo (user, login = undefined) {
    if (login) {
      return await iotlab.api.put(`/users/${login}`, user)
    } else {
      return await iotlab.api.put('/user', user)
    }
  },

  async activateUser (login) {
    return await iotlab.api.put(`/users/${login}`, {'status': 'active'})
  },

  async deactivateUser (login) {
    return await iotlab.api.put(`/users/${login}`, {'status': 'pending'})
  },

  async setAdminRole (login, bool) {
    return await iotlab.api.put(`/users/${login}`, {'admin': bool})
  },

  async sendMail (recipients, subject, message) {
    await iotlab.api.post('/users/email', {
      recipients: recipients,
      subject: subject,
      body: message,
    })
  },

  // EXPERIMENT API

  async getUserExperimentsCount () {
    return await iotlab.apiv1.get('/experiments?total').then(resp => resp.data)
  },

  async getUserExperiments (state = 'Terminated,Error,Running,Finishing,Resuming,toError,Waiting,Launching,Hold,toLaunch,toAckReservation,Suspended') {
    return await iotlab.apiv1.get('/experiments', {'params': {state: state}}).then(resp => resp.data.items)
  },

  // OTHER API

  async getSites () {
    return await iotlab.api.get('/sites').then(resp => resp.data.items)
  },

  async getSitesDetails () {
    return await iotlab.api.get('/sites/details').then(resp => resp.data.items)
  },

  async getNodes () {
    return await iotlab.api.get('/nodes').then(resp => resp.data.items)
  },

  // API V1

  async getStats () {
    return await iotlab.apiv1.get('/stats').then(resp => resp.data)
  },

  async getSiteResources () {
    return await iotlab.apiv1.get('/experiments?resources').then(resp => resp.data.items)
  },

}
