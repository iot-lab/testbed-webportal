var webpack = require('webpack')

module.exports = {
  lintOnSave: 'error',
  baseUrl: process.env.NODE_ENV === 'production'
    ? process.env.VUE_APP_IOTLAB_PATH
    : '/',
  configureWebpack: {
    plugins: [
      new webpack.ProvidePlugin({
        $: 'jquery',
        jQuery: 'jquery',
        'window.jQuery': 'jquery',
        Popper: ['popper.js', 'default'],
        moment: 'moment',
        'window.moment': 'moment',
      }),
    ],
  },
  chainWebpack: config => {
    // HTML Loader
    config.module
      .rule('html')
      .test(/\.html$/)
      .use('html-loader/loader')
      .loader('html-loader')
      .end()
  },
}
