// https://docs.cypress.io/guides/guides/plugins-guide.html

// const path = require("path")
// const webpack = require("@cypress/webpack-preprocessor")
// const DefinePlugin = require("webpack").DefinePlugin

module.exports = (on, config) => {
  // (Uncomment to define env variables so that cypress tests can have access to process.env)
  // const options = Object.assign({}, webpack.defaultOptions)
  // options.webpackOptions.plugins = [
  //   new DefinePlugin({
  //     'process.env.VUE_APP_IOTLAB_HOST': '"devwww.iot-lab.info"',
  //     'process.env.VUE_APP_IOTLAB_PATH': '"testbed"',
  //   }),
  // ]
  // on("file:preprocessor", webpack(options))
  //
  // (extra options samples:)
  // options.webpackOptions.resolve = {
  //   alias: {
  //     // Add `@` alias to be compatible with source config
  //     "@": path.resolve(path.join(__dirname, "../../..", "src"))
  //   }
  // }
  // // Ignore .babelrc from the project root
  // options.webpackOptions.module.rules[0].use[0].options.babelrc = false
  // const options = {
  //   // send in the options from your webpack.config.js, so it works the same
  //   // as your app's code
  //   webpackOptions: require('../../webpack.config'),
  //   watchOptions: {},
  // }

  return Object.assign({}, config, {
    fixturesFolder: 'tests/e2e/fixtures',
    integrationFolder: 'tests/e2e/specs',
    screenshotsFolder: 'tests/e2e/screenshots',
    videosFolder: 'tests/e2e/videos',
    supportFile: 'tests/e2e/support/index.js',
    env: {
      IOTLAB_WEBPORTAL_PASSWD: process.env.IOTLAB_WEBPORTAL_PASSWD,
      VUE_APP_IOTLAB_HOST: process.env.VUE_APP_IOTLAB_HOST,
      APP_ROOT: process.env.VUE_APP_IOTLAB_PATH || '/',
    },
  })
}
