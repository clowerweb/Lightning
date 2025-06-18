describe('Lightning Default Template', () => {
  it('loads the homepage', () => {
    cy.visit('/')
    cy.contains('h1', 'Lightning meets Nuxt')
  });

  it('loads the about page', () => {
    cy.visit('/about')
    cy.contains('h1', 'Getting Started')
  });

  it('loads the admin page', () => {
    cy.visit('/admin/login')
    cy.contains('h1', 'Admin Login')
  });

  it('serves robots.txt correctly', () => {
    cy.request('/robots.txt').its('body').should('contain', 'User-agent: *')
  });
});
