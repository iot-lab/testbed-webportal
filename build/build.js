require('./check-versions')()

process.env.NODE_ENV = 'production'

// IOTLAB_ENV environnements:
// - dev       -> deploy to devwww.iot-lab.info/testbed
// - dev-beta  -> deploy to devwww.iot-lab.info/testbed-beta
// - prod      -> deploy to www.iot-lab.info/testbed
// - prod-beta -> deploy to www.iot-lab.info/testbed-beta

if (!process.env.IOTLAB_ENV) {
  process.env.IOTLAB_ENV = 'dev-beta'
}

process.env.IOTLAB_HOST = process.env.IOTLAB_ENV.startsWith('prod') ? 'www.iot-lab.info' : 'devwww.iot-lab.info'
process.env.IOTLAB_PATH = process.env.IOTLAB_ENV.endsWith('-beta') ? 'testbed-beta' : 'testbed'

var chalk = require('chalk')

console.log(chalk.cyan(`Building for IOTLAB_ENV ${process.env.IOTLAB_ENV}`))
console.log(chalk.yellow(`=> Deploy to ${process.env.IOTLAB_HOST}/${process.env.IOTLAB_PATH}\n`))

var ora = require('ora')
var rm = require('rimraf')
var path = require('path')
var webpack = require('webpack')
var config = require('../config')
var webpackConfig = require('./webpack.prod.conf')

var spinner = ora('building for production...')
spinner.start()

rm(path.join(config.build.assetsRoot, config.build.assetsSubDirectory), err => {
  if (err) throw err
  webpack(webpackConfig, function (err, stats) {
    spinner.stop()
    if (err) throw err
    process.stdout.write(stats.toString({
      colors: true,
      modules: false,
      children: false,
      chunks: false,
      chunkModules: false
    }) + '\n\n')

    console.log(chalk.cyan('  Build complete.\n'))
    console.log(chalk.yellow(
      '  Tip: built files are meant to be served over an HTTP server.\n' +
      '  Opening index.html over file:// won\'t work.\n'
    ))
  })
})
