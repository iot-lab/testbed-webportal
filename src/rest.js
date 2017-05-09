import axios from 'axios'

export const iotlab = {

  api: axios.create({baseURL: 'https://devwww.iot-lab.info/rest/'}),

  create (username, password) {
    this.api = axios.create({
      baseURL: 'https://devwww.iot-lab.info/rest/',
      auth: {
        username: username,
        password: password,
      },
    })
  },

}
