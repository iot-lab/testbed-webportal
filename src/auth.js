import axios from 'axios'
import {iotlab} from './rest'

export const auth = {

  loggedIn: sessionStorage.getItem('loggedIn') || false,
  isAdmin: sessionStorage.getItem('isAdmin') || false,
  username: sessionStorage.getItem('username') || '',

  async doLogin (username, password) {
    // let iotlab = axios.create({
    //   baseURL: 'https://devwww.iot-lab.info/rest/',
    //   auth: {
    //     username: username,
    //     password: password,
    //   },
    // })
    iotlab.create(username, password)
    sessionStorage.setItem('apiAuth', JSON.stringify({
      username: username,
      password: password,
    }))

    await axios.all([
      iotlab.api.get(`/users/${username}?login`),
      iotlab.api.get(`/users/${username}/isadmin`),
    ])
    .then(axios.spread((user, admin) => {
      // when all requests successful
      this.loggedIn = true
      this.username = username
      this.isAdmin = (admin.data === 'Success')
      sessionStorage.setItem('loggedIn', this.loggedIn)
      sessionStorage.setItem('isAdmin', this.isAdmin)
      sessionStorage.setItem('username', this.username)
    }))
    .catch(err => {
      // console.log( err )
      this.isAdmin = false
      this.loggedIn = false
      this.username = ''
      throw err
    })
  },

  doLogout () {
    this.loggedIn = false
    sessionStorage.removeItem('loggedIn')
    sessionStorage.removeItem('apiAuth')
  },
}

