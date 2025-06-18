import { defineConfig } from 'cypress';

export default defineConfig({
  e2e: {
    baseUrl: 'https://lightning.local',
    specPattern: 'cypress/e2e/**/*.cy.{js,jsx,ts,tsx}',
    supportFile: false,
  },
});
