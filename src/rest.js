import axios from 'axios'

const API_URL = `https://${process.env.VUE_APP_IOTLAB_HOST}/api/`

export const iotlab = {

  api: axios.create({baseURL: API_URL, auth: JSON.parse(localStorage.getItem('apiAuth') || '{}')}),

  create (username, password) {
    this.api = axios.create({
      baseURL: API_URL,
      auth: {
        username: username,
        password: password,
      },
    })
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

  async deleteUser (login = undefined) {
    if (login) {
      await iotlab.api.delete(`/users/${login}`)
    } else {
      await iotlab.api.delete(`/user`)
    }
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
    return iotlab.api.get('/user/keys').then(resp => resp.data.sshkeys)
  },

  async modifySSHkeys (keys) {
    return iotlab.api.post('/user/keys', {'sshkeys': keys})
  },

  async deleteSSHkey (id) {
    return iotlab.api.delete(`/user/keys/${id}`)
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
    return iotlab.api.get('/users', {params: params}).then(resp => resp.data)
  },

  async getUserInfo (login = undefined) {
    if (login) {
      return iotlab.api.get(`/users/${login}`).then(resp => resp.data)
    } else {
      return iotlab.api.get('/user').then(resp => resp.data)
    }
  },

  async setUserInfo (user, login = undefined) {
    if (login) {
      return iotlab.api.put(`/users/${login}`, user)
    } else {
      return iotlab.api.put('/user', user)
    }
  },

  async activateUser (login) {
    return iotlab.api.put(`/users/${login}`, {'status': 'active'})
  },

  async deactivateUser (login) {
    return iotlab.api.put(`/users/${login}`, {'status': 'inactive'})
  },

  async setUserGroups (login, groups) {
    return iotlab.api.put(`/users/${login}`, {'groups': groups})
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
    return iotlab.api.get('/experiments/total').then(resp => resp.data)
  },

  async getAllExperimentsCount (user) {
    if (user) {
      return iotlab.api.get(`/experiments/total/users/${user}`).then(resp => resp.data)
    } else {
      return iotlab.api.get(`/experiments/total/all`).then(resp => resp.data)
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
      return iotlab.api.get('/experiments', {params: params}).then(resp => resp.data.items)
    } else if (user) {
      return iotlab.api.get(`/experiments/users/${user}`, {params: params}).then(resp => resp.data.items)
    } else {
      return iotlab.api.get('/experiments/all', {params: params}).then(resp => resp.data.items)
    }
  },

  async getRunningExperiments () {
    return iotlab.api.get('/experiments/running').then(resp => resp.data.items)
  },

  async getExperiment (id) {
    return iotlab.api.get(`/experiments/${id}`).then(resp => resp.data)
  },

  async getExperimentArchive (id) {
    return iotlab.api.get(`/experiments/${id}/data`, {responseType: 'arraybuffer'}).then(resp => resp.data)
  },

  async getExperimentDeployment (id) {
    return iotlab.api.get(`/experiments/${id}/deployment`).then(resp => resp.data)
  },

  async stopExperiment (id) {
    return iotlab.api.delete(`/experiments/${id}`)
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
    // attached firmware names should be unique
    let attachedFirmwares = []
    for (let asso of firmwareassociations || []) {
      if (asso.firmwarename in firmwares && !attachedFirmwares.includes(asso.firmwarename)) {
        formData.append(asso.firmwarename, new Blob([firmwares[asso.firmwarename]], {type: 'application/octet-stream'}), asso.firmwarename)
        attachedFirmwares.push(asso.firmwarename)
      }
    }
    return iotlab.api.post('/experiments', formData).then(resp => resp.data)
  },

  async checkFirmware (fileString) {
    const formData = new FormData()
    formData.append('firmware.bin', fileString)
    return iotlab.api.post('/firmwares/checker', formData).then(resp => resp.data)
  },

  async flashFirmware (id, nodes, fileString) {
    const formData = new FormData()
    formData.append(`experiment${id}.json`, JSON.stringify(nodes))
    formData.append('firmware.bin', new Blob([fileString], {type: 'application/octet-stream'}), 'firmware.bin')
    return iotlab.api.post(`/experiments/${id}/nodes/flash`, formData).then(resp => resp.data)
  },

  async flashResourcesFirmware (id, nodes, name) {
    return iotlab.api.post(`/experiments/${id}/nodes/flash/${name}`, nodes).then(resp => resp.data)
  },

  async updateMonitoring (id, nodes, name) {
    return iotlab.api.post(`/experiments/${id}/nodes/monitoring/${name}`, nodes).then(resp => resp.data)
  },

  async updateExperimentMobilityCircuit (id, nodes, name) {
    return iotlab.api.post(`/experiments/${id}/robots/mobility/${name}`, nodes).then(resp => resp.data)
  },

  async reloadExperiment (id) {
    return iotlab.api.post(`/experiments/${id}/reload`, {}).then(resp => resp.data)
  },

  // USER GROUPS

  async getUserGroups () {
    return iotlab.api.get('/groups').then(resp => resp.data)
  },

  async createGroup (name) {
    return iotlab.api.post(`/groups`, {name: name})
  },

  async deleteGroup (group) {
    return iotlab.api.delete(`/groups/${group}`)
  },

  // OTHER API

  async getSites () {
    return iotlab.api.get('/sites').then(resp => resp.data.items)
  },

  async getSitesDetails () {
    return iotlab.api.get('/sites/details').then(resp => resp.data.items)
  },

  async getNodes () {
    return iotlab.api.get('/nodes').then(resp => resp.data.items)
  },

  async updateNodesProperties () {
    return iotlab.api.put('/nodes')
  },

  async updateNodesPropertiesOldApi () {
    const OLD_API = `https://${process.env.VUE_APP_IOTLAB_HOST}/rest/`
    const oldApi = axios.create({baseURL: OLD_API, auth: JSON.parse(localStorage.getItem('apiAuth') || '{}')})
    return oldApi.post('/admin/nodes')
  },

  async getNodesIds () {
    return iotlab.api.get('/nodes/ids').then(resp => resp.data.items)
  },

  async getExperimentNodes (id) {
    return iotlab.api.get(`/experiments/${id}/nodes`).then(resp => resp.data.items)
  },

  async getExperimentToken (id) {
    return iotlab.api.get(`/experiments/${id}/token`).then(resp => resp.data.token)
  },

  async sendNodesCommand (id, cmd, nodes) {
    // cmd in [start, stop, reset, flash-idle, profile-reset, debug-start, debug-stop]
    return iotlab.api.post(`/experiments/${id}/nodes/${cmd}`, nodes).then(resp => resp.data)
  },

  // STORES (firmwares, profiles, etc)

  async getMobilityCircuits () {
    return iotlab.api.get('/mobilities/circuits').then(resp => resp.data.items)
  },

  async getMobilityCircuit (name) {
    return iotlab.api.get(`/mobilities/circuits/${name}`).then(resp => resp.data)
  },

  async getRobotsSiteMapImage (site) {
    return iotlab.api.get(`/robots/${site}/map/image`, {
      responseType: 'arraybuffer',
      headers: {
        'Access-Control-Allow-Origin': '*',
      },
    }).then(resp => Buffer.from(resp.data, 'binary').toString('base64')
    )
  },

  async getRobotsSiteMapConfig (site) {
    return iotlab.api.get(`/robots/${site}/map/config`).then(resp => resp.data)
  },

  async createMobilityCircuit (circuit) {
    return iotlab.api.post(`/mobilities/circuits`, circuit)
  },

  async updateMobilityCircuit (name, circuit) {
    return iotlab.api.put(`/mobilities/circuits/${name}`, circuit)
  },

  async deleteMobilityCircuit (name) {
    return iotlab.api.delete(`/mobilities/circuits/${name}`)
  },

  async getMonitoringProfiles () {
    return iotlab.api.get('/monitoring').then(resp => resp.data)
  },

  async getMonitoringProfile (name) {
    return iotlab.api.get(`/monitoring/${name}`).then(resp => resp.data)
  },

  async updateMonitoringProfile (name, profile) {
    return iotlab.api.put(`/monitoring/${name}`, profile)
  },

  async createMonitoringProfile (profile) {
    return iotlab.api.post(`/monitoring`, profile)
  },

  async deleteMonitoringProfile (name) {
    return iotlab.api.delete(`/monitoring/${name}`)
  },

  async getFirmwares () {
    return iotlab.api.get('/firmwares').then(resp => resp.data)
  },

  async getFirmware (name) {
    return iotlab.api.get(`/firmwares/${name}`).then(resp => resp.data)
  },

  async getFirmwareFile (name) {
    return iotlab.api.get(`/firmwares/${name}/file`, {responseType: 'arraybuffer'}).then(resp => resp.data)
  },

  async updateFirmware (name, firmware, fileString) {
    const formData = new FormData()
    formData.append(`firmware.json`, JSON.stringify(firmware))
    if (fileString) {
      formData.append('firmware.bin', new Blob([fileString], {type: 'application/octet-stream'}), 'firmware.bin')
    }
    return iotlab.api.put(`/firmwares/${name}`, formData).then(resp => resp.data)
  },

  async createFirmware (firmware, fileString) {
    const formData = new FormData()
    formData.append(`firmware.json`, JSON.stringify(firmware))
    formData.append('firmware.bin', new Blob([fileString], {type: 'application/octet-stream'}), 'firmware.bin')
    return iotlab.api.post(`/firmwares`, formData).then(resp => resp.data)
  },

  async deleteFirmware (name) {
    return iotlab.api.delete(`/firmwares/${name}`)
  },
}
