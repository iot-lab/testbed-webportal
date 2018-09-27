// ***********************************************
// This example commands.js shows you how to
// create various custom commands and overwrite
// existing commands.
//
// For more comprehensive examples of custom
// commands please read more here:
// https://on.cypress.io/custom-commands
// ***********************************************
//
// -- This is a parent command --
// Cypress.Commands.add("login", (email, password) => { ... })
//
// -- This is a child command --
// Cypress.Commands.add("drag", { prevSubject: 'element'}, (subject, options) => { ... })
//
// -- This is a dual command --
// Cypress.Commands.add("dismiss", { prevSubject: 'optional'}, (subject, options) => { ... })
//
// -- This is will overwrite an existing command --
// Cypress.Commands.overwrite("visit", (originalFn, url, options) => { ... })

// import {auth} from '../../../src/auth'

Cypress.Commands.add('login', (userType, options = {}) => {
  const types = {
    admin: {
      login: 'admin',
      password: 'password',
      admin: true,
    },
    user: {
      login: 'webportal',
      password: Cypress.env('IOTLAB_WEBPORTAL_PASSWD'),
      admin: false,
    }
  }

  const user = types[userType]

  // auth.doLogin(user.login, user.password)

  cy.server()
  cy.route('/api/user', {"firstName":"Webportal","lastName":"Webportal","email":"admin@iot-lab.info","organization":"Webportal","city":"Webportal","country":"France","login":"toto","motivations":"Webportal account with admin role access","status":"active","created":"2017-10-27T08:37:08Z","groups":["admin"],"sshkeys":["no need"]})
  cy.visit('/login')
  cy.get('input[placeholder=Username]').type(user.login)
  cy.get('input[placeholder=Password]').type(user.password + '{enter}').should(() => {
    expect(localStorage.getItem('loggedIn')).to.eq('true')
    expect(localStorage.getItem('username')).to.eq(user.login)
  })
})

// Cypress.Commands.add('login', (userType, options = {}) => {
//   this is an example of skipping your UI and logging in programmatically

//   setup some basic types
//   and user properties
//   const types = {
//     admin: {
//       login: 'admin',
//       password: 'password',
//       admin: true,
//     },
//     user: {
//       login: 'webportal',
//       password: Cypress.env('IOTLAB_WEBPORTAL_PASSWD'),
//       admin: false,
//     }
//   }

//   // grab the user
//   const user = types[userType]

//   // create the user first in the DB
//   cy.request({
//     url: '/seed/users', // assuming you've exposed a seeds route
//     method: 'POST',
//     body: user,
//   })
//   .its('body')
//   .then((body) => {
//     // assuming the server sends back the user details
//     // including a randomly generated password
//     //
//     // we can now login as this newly created user
//     cy.request({
//       url: '/login',
//       method: 'POST',
//       body: {
//         email: body.email,
//         password: body.password,
//       }
//     })
//   })
// })