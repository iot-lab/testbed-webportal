import axios from 'axios'

const API_V1_URL = `https://${process.env.IOTLAB_HOST}/rest/`
const API_V2_URL = `https://${process.env.IOTLAB_HOST}/api/`

export const iotlab = {

  apiv1: axios.create({baseURL: API_V1_URL, auth: JSON.parse(localStorage.getItem('apiAuth') || '{}')}),
  apiv2: axios.create({baseURL: API_V2_URL, auth: JSON.parse(localStorage.getItem('apiAuth') || '{}')}),
  api: axios.create({baseURL: API_V2_URL, auth: JSON.parse(localStorage.getItem('apiAuth') || '{}')}),

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

  async signup (user, nbUsers) {
    if (nbUsers) {
      // create multiple user accounts
      await iotlab.api.post(`/users?nbusers=${nbUsers}`, user)
    } else {
      // create single user account
      await iotlab.api.post('/users', user)
    }
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

  async getUsers ({status, group, search} = {}) {
    let params = {}
    if (status) {
      params.status = status
    }
    if (group) {
      params.group = group
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

  async setUserGroups (login, groups) {
    return await iotlab.api.put(`/users/${login}`, {'groups': groups})
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

  async getAllExperimentsCount (user) {
    if (user) {
      return await iotlab.api.get(`/experiments/total/users/${user}`).then(resp => resp.data)
    } else {
      return await iotlab.api.get(`/experiments/total/all`).then(resp => resp.data)
    }
  },

  async getAllExperiments ({limit, offset, user, state = 'Terminated,Error,Running,Finishing,Resuming,toError,Waiting,Launching,Hold,toLaunch,toAckReservation,Suspended'} = {}) {
    let params = {}
    if (state) {
      params.state = state
    }
    if (limit) {
      params.limit = Math.min(limit, 500) // IoT-LAB api restrict queries to 500 entries
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

  async getExperimentArchive (id) {
    return await iotlab.api.get(`/experiments/${id}/data`).then(resp => resp.data)
    // return
  },

  async getExperimentDeployment (id) {
    return await iotlab.api.get(`/experiments/${id}/deployment`).then(resp => resp.data)
  },

  async stopExperiment (id) {
    return await iotlab.api.delete(`/experiments/${id}`)
  },

  async submitExperiment ({type, name, duration, reservation, nodes, firmwareassociations, firmwares, profileassociations, profiles} = {}) {
    const formData = new FormData()
    formData.append('experiment.json', JSON.stringify({
      type: type,
      duration: duration,
      reservation: reservation,
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

  async flashFirmware (id, nodes, fileString) {
    const formData = new FormData()
    formData.append(`experiment${id}.json`, JSON.stringify(nodes))
    formData.append('firmware.bin', new Blob([fileString], {type: 'application/octet-stream'}), 'firmware.bin')
    return await iotlab.api.post(`/experiments/${id}/nodes/flash`, formData).then(resp => resp.data)
  },

  async reloadExperiment (id) {
    return await iotlab.api.post(`/experiments/${id}/reload`, {}).then(resp => resp.data)
  },

  // USER GROUPS

  async getUserGroups () {
    return await iotlab.api.get('/groups').then(resp => resp.data)
  },

  async createGroup (name) {
    return await iotlab.api.post(`/groups`, {name: name})
  },

  async deleteGroup (group) {
    return await iotlab.api.delete(`/groups/${group}`)
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

  async updateNodesProperties () {
    return await iotlab.api.put('/nodes')
  },

  async getNodesIds () {
    return await iotlab.api.get('/nodes/ids').then(resp => resp.data.items)
  },

  async sendNodesCommand (id, cmd, nodes) {
    // cmd in [start, stop, reset, flash-idle, profile-reset, debug-start, debug-stop]
    return await iotlab.api.post(`/experiments/${id}/nodes/${cmd}`, nodes).then(resp => resp.data)
  },

  async getMonitoringProfiles () {
    return await iotlab.api.get('/monitoring').then(resp => resp.data)
  },

  async getMonitoringProfile (name) {
    return await iotlab.api.get(`/monitoring/${name}`).then(resp => resp.data)
  },

  async updateMonitoringProfile (name, profile) {
    return await iotlab.api.put(`/monitoring/${name}`, profile)
  },

  async createMonitoringProfile (profile) {
    return await iotlab.api.post(`/monitoring`, profile)
  },

  async deleteMonitoringProfile (name) {
    return await iotlab.api.delete(`/monitoring/${name}`)
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
