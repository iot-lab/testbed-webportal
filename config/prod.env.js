if (!process.env.IOTLAB_HOST) {
  process.env.IOTLAB_HOST = process.env.IOTLAB_ENV === 'prod' ? 'www.iot-lab.info' : 'devwww.iot-lab.info'
}

module.exports = {
  NODE_ENV: '"production"',
  IOTLAB_HOST: `"${process.env.IOTLAB_HOST}"`
}
