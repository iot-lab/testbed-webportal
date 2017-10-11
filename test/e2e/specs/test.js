// For authoring Nightwatch tests, see
// http://nightwatchjs.org/guide#usage

module.exports = {
  'show login': function (browser) {
    // automatically uses dev Server port from /config.index.js
    // default: http://localhost:8080
    // see nightwatch.conf.js
    const devServer = browser.globals.devServerURL
    
    browser
      .url(devServer)
      .waitForElementVisible('#app', 1000)
      .assert.elementPresent('form')
      // .assert.elementCount('form', 1)
      .assert.containsText('form > button', 'Log in')
      .end()
  },

  'show dashboard after login': function (browser) {
    const devServer = browser.globals.devServerURL
    browser
      .url(devServer)
      .waitForElementVisible('#app', 1000)
      .assert.elementPresent('form')
      .setValue('input[placeholder=Username]', 'webportal')
      .setValue('input[placeholder=Password]', browser.globals.webPortalPwd)
      .submitForm('form')
      .waitForElementVisible('.navbar', 2000)
      .assert.containsText('h2', 'My experiments')
      .end()
  }   
}
