import axios from 'axios'
import {iotlab} from './rest'

export const auth = {

  loggedIn: false,
  isAdmin: false,
  username: '',

  async doLogin (username, password) {
    // let iotlab = axios.create({
    //   baseURL: 'https://devwww.iot-lab.info/rest/',
    //   auth: {
    //     username: username,
    //     password: password,
    //   },
    // })
    iotlab.create(username, password)

    await axios.all([
      iotlab.api.get(`/users/${username}?login`),
      iotlab.api.get(`/users/${username}/isadmin`),
    ])
    .then(axios.spread((user, admin) => {
      // when all requests successful
      this.loggedIn = true
      this.username = username
      this.isAdmin = (admin.data === 'Success')
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
  },
}

