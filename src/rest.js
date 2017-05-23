import axios from 'axios'

// const API_URL = 'http://localhost:6060/devwww.iot-lab.info:443/rest/'
const API_URL = 'https://devwww.iot-lab.info/rest/'

export const iotlab = {

  api: axios.create({baseURL: API_URL}),

  create (username, password) {
    this.api = axios.create({
      baseURL: API_URL,
      auth: {
        username: username,
        password: password,
      },
    })
  },

  async signup (user) {
    await iotlab.api.post('/users', user)
  },

  async resetPassword (email) {
    await iotlab.api.put(`/users/${email}?resetpassword`, {'email': email})
  },

  async changePassword (oldPwd, newPwd, confirmPwd) {
    await iotlab.api.put('/users/password', {
      'oldPassword': oldPwd,
      'newPassword': newPwd,
      'confirmNewPassword': confirmPwd,
    })
    .then(resp => {
      if (resp.data === 'Success') {
        return Promise.resolve()
      }
      return Promise.reject()
    })
    .catch(err => {
      throw err
    })
  },

  async getSSHkeys () {
    return await iotlab.api.get('/users/sshkeys').then(resp => resp.data.sshkeys)
  },

  async modifySSHkeys (keys) {
    return await iotlab.api.put('/users/sshkeys', {'sshkeys': keys})
  },
}
