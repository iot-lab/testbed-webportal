import axios from 'axios'
import {iotlab} from './rest'

export const auth = {

  loggedIn: localStorage.getItem('loggedIn') || false,
  isAdmin: localStorage.getItem('isAdmin') || false,
  username: localStorage.getItem('username') || '',

  async doLogin (username, password) {
    // let iotlab = axios.create({
    //   baseURL: 'https://devwww.iot-lab.info/rest/',
    //   auth: {
    //     username: username,
    //     password: password,
    //   },
    // })
    iotlab.create(username, password)
    localStorage.setItem('apiAuth', JSON.stringify({
      username: username,
      password: password,
    }))

    await axios.all([
      iotlab.apiv1.get(`/users/${username}?login`),
      iotlab.apiv1.get(`/users/${username}/isadmin`),
    ])
    .then(axios.spread((user, admin) => {
      // when all requests successful
      this.loggedIn = true
      this.username = username
      this.isAdmin = (admin.data === 'Success')
      localStorage.setItem('loggedIn', this.loggedIn)
      localStorage.setItem('isAdmin', this.isAdmin)
      localStorage.setItem('username', this.username)
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
    localStorage.removeItem('loggedIn')
    localStorage.removeItem('apiAuth')
  },
}

