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

  async activateAccount (hash) {
    await iotlab.api.post('/users/activate/', {'hash': hash})
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
    return await iotlab.api.get('/experiments/total').then(resp => resp.data)
  },

  async getAllExperiments ({limit, offset, user, state = 'Terminated,Error,Running,Finishing,Resuming,toError,Waiting,Launching,Hold,toLaunch,toAckReservation,Suspended'} = {}) {
    let params = {}
    if (state) {
      params.state = state
    }
    if (limit) {
      params.limit = limit
    }
    if (offset) {
      params.offset = offset
    }
    if (user === '@self') {
      return await iotlab.api.get('/experiments', {params: params}).then(resp => resp.data.items)
    } else if (user) {
      return await iotlab.api.get(`/experiments/users/${user}`, {params: params}).then(resp => resp.data.items)
    } else {
      return await iotlab.api.get('/experiments/all', {params: params}).then(resp => resp.data.items)
    }
  },

  async getExperiment (id) {
    return await iotlab.api.get(`/experiments/${id}`).then(resp => resp.data)
  },

  async submitExperiment ({type, name, duration, nodes, firmwareassociations, firmwares, profileassociations, profiles} = {}) {
    const formData = new FormData()
    formData.append('experiment.json', JSON.stringify({
      type: type,
      duration: duration,
      name: name,
      nodes: nodes,
      firmwareassociations: firmwareassociations,
      profileassociations: profileassociations,
    }))
    for (let asso of firmwareassociations || []) {
      formData.append(asso.firmwarename, new Blob([firmwares[asso.firmwarename]], {type: 'application/octet-stream'}), asso.firmwarename)
    }
    return await iotlab.api.post('/experiments', formData).then(resp => resp.data)
  },

  async checkFirmware (fileString) {
    const formData = new FormData()
    formData.append('firmware.bin', fileString)
    return await iotlab.api.post('/firmwares/checker', formData).then(resp => resp.data)
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

// GET /experiments?state(Running,Terminated),limit(500),offset(0)  (user:current)
// POST /experiments
// GET /experiments/total
// GET /experiments/:id
// GET /experiments/:id/anystate ???
// GET /experiments/:id/state
// GET /experiments/:id/data
// GET /experiments/:id/result
// GET /experiments/:id/nodes
// GET /experiments/:id/nodes_id
// GET /experiments/:id/start (get start time)
// DEL /experiments/:id
// POST /experiments/:id/reload
// POST /experiments/:id/scripts/run
// POST /experiments/:id/scripts/kill
// POST /experiments/:id/scripts/status
// POST /experiments/:id/scripts/flash
// POST /experiments/:id/nodes/monitoring
// POST /experiments/:id/nodes/:cmd     { "start", "stop", "reset", "debug-start", "debug-stop", "update-idle", "profile-reset" };
// POST /experiments/:id/nodes/monitoring/:name

// GET /nodes?site(all),archi(all),state(all)
// GET /nodes?site(all),archi(all),state(all)

// note: send mail par l'api ?
// admin filter when pending....
