// https://docs.cypress.io/api/introduction/api.html

describe('Login', () => {

  // beforeEach(() => {
    // cy.visit(Cypress.env('APP_ROOT'))
    // cy.get('.navbar-nav').contains('Commands').click()
  // })

  it('Should show login form', () => {
    cy.visit('/')
    cy.location('hash').should('include', 'login')
    cy.contains('h2', 'Welcome to FIT IoT-LAB')
    cy.contains('button', 'Log in')
  })

  it('Log in should set auth and redirect to dashboard', () => {
    cy.visit('/')
    cy.get('input[placeholder=Username]').type('webportal')
    cy.get('input[placeholder=Password]').type(Cypress.env('IOTLAB_WEBPORTAL_PASSWD') + '{enter}').should(() => {
      expect(localStorage.getItem('loggedIn')).to.eq('true')
      expect(localStorage.getItem('username')).to.eq('webportal')
    })
      
    cy.location('hash').should('include', 'dashboard')
    // cy.location('pathname').should('include', 'dashboard')
    
    cy.contains('h2', 'My experiments')
  })

  it('Log out should clear auth and redirect to login', () => {
    cy.visit('/')
    cy.get('input[placeholder=Username]').type('webportal')
    cy.get('input[placeholder=Password]').type(Cypress.env('IOTLAB_WEBPORTAL_PASSWD') + '{enter}').should(() => {
      expect(localStorage.getItem('loggedIn')).to.eq('true')
    })

    cy.get('i[aria-label="Logout"]').click().should(() => {
      expect(localStorage.getItem('loggedIn')).to.be.null
    })

    cy.location('hash').should('include', 'login')
  })

})
