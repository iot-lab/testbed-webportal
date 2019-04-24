// import axios from 'axios'
import {iotlab} from './rest'

export const auth = {

  loggedIn: localStorage.getItem('loggedIn') === 'true',
  isAdmin: localStorage.getItem('isAdmin') === 'true',
  username: localStorage.getItem('username') || '',

  async doLogin (username, password) {
    iotlab.create(username, password)
    localStorage.setItem('apiAuth', JSON.stringify({
      username: username,
      password: password,
    }))

    await iotlab.getUserInfo()
      .then(user => {
        this.loggedIn = true
        this.username = user.login
        this.isAdmin = user.groups.includes('admin')
        localStorage.setItem('loggedIn', this.loggedIn)
        localStorage.setItem('isAdmin', this.isAdmin)
        localStorage.setItem('username', this.username)
      })
      .catch(err => {
      // console.log(err)
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
    localStorage.removeItem('isAdmin')
  },
}
