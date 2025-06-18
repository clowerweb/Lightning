// https://nuxt.com/docs/api/configuration/nuxt-config
// @ts-ignore - defineNuxtConfig will be available at runtime
export default defineNuxtConfig({
  // Disable devtools to prevent errors
  devtools: { enabled: false },
  ssr: false,
  typescript: {
    strict: false, // Relax typescript checking
    shim: false,
  },

  // Configure the app to work with the PHP backend
  app: {
    baseURL: '/admin/', // Make sure this matches the PHP routing
    head: {
      title: 'Lightning Admin',
      htmlAttrs: {
        lang: 'en',
      },
      meta: [
        { name: 'viewport', content: 'width=device-width, initial-scale=1', },
        { name: 'description', content: 'Lightning PHP framework with Nuxt', },
      ],
      link: [
        { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico', },
      ]
    }
  },

  // Add runtime config for API base URL
  runtimeConfig: {
    public: {
      apiBase: '/api',
    }
  },

  // Development server configuration
  devServer: {
    port: 3001,
    host: '0.0.0.0',
  },

  vite: {
    server: {
      hmr: {
        port: 24679,
      },
    },
  },

  modules: [
    '@nuxtjs/tailwindcss',
  ],
});