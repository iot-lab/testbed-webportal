// https://docs.cypress.io/api/introduction/api.html

describe('Login', () => {

  // beforeEach(() => {
    // cy.visit(Cypress.env('APP_ROOT'))
    // cy.get('.navbar-nav').contains('Commands').click()
  // })

  it('Should show login form', () => {
    cy.visit('/')
    cy.location('pathname').should('include', 'login')
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
      
    cy.location('pathname').should('include', 'dashboard')
    cy.location('pathname').should('not.include', 'login')
    
    cy.contains('h4', 'My experiments')
  })

  it('test', () => {
    cy.login('user')
    // cy.visit('/#/dashboard')      
    cy.contains('h4', 'My experiments')
  })

  it('Log out should clear auth and redirect to login', () => {
    cy.visit('/')
    cy.get('input[placeholder=Username]').type('webportal')
    cy.get('input[placeholder=Password]').type(Cypress.env('IOTLAB_WEBPORTAL_PASSWD') + '{enter}').should(() => {
      expect(localStorage.getItem('loggedIn')).to.eq('true')
    })

    cy.get('i[aria-label="Logout"]').click({force: true}).should(() => {
      expect(localStorage.getItem('loggedIn')).to.be.null
    })

    cy.location('pathname').should('include', 'login')
  })

})
