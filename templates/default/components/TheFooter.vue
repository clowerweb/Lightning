<template>
  <footer class="bg-gray-900 border-t border-gray-200 py-8">
    <div class="container">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <div class="md:col-span-2">
          <RouterLink to="/" class="flex items-center w-[7.5rem]">
            <img src="/assets/img/logo.png" height="47" width="120" alt="Lightning logo" />
            <span class="sr-only">Lightning</span>
          </RouterLink>

          <p class="text-white text-sm max-w-md my-6">
            A free, open-source headless OOP PHP framework with a decoupled front-end. Build scalable, modern applications with a clean GraphQL API architecture.
          </p>

          <div class="flex space-x-4">
            <a href="https://github.com/clowerweb/lightning" class="text-white hover:text-lightning-yellow transition-colors duration-200" target="_blank" rel="noopener noreferrer">
              <span class="sr-only">GitHub</span>
              <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.942.359.31.678.921.678 1.856 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.481C19.139 20.2 22 16.442 22 12.017 22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
              </svg>
            </a>
          </div>
        </div>

        <div>
          <h3 class="text-sm font-semibold text-white tracking-wider uppercase mb-4">Product</h3>
          <ul class="space-y-3 text-sm list-none">
            <li><NuxtLink to="/" class="text-white hover:text-lightning-yellow transition-colors duration-200">Home</NuxtLink></li>
          </ul>
        </div>

        <div>
          <h3 class="text-sm font-semibold text-white tracking-wider uppercase mb-4">Support</h3>
          <ul class="space-y-3 text-sm list-none">
            <li><NuxtLink to="/about" class="text-white hover:text-lightning-yellow transition-colors duration-200">Get Started</NuxtLink></li>
          </ul>
        </div>
      </div>

      <div class="mt-12 pt-8 border-t border-gray-200 flex flex-col md:flex-row justify-between">
        <p class="text-sm text-gray-400">&copy; {{ new Date().getFullYear() }} Lightning. All rights reserved.</p>
        <div class="mt-4 md:mt-0 flex items-center">
          <a href="#" class="text-sm text-white hover:text-lightning-yellow transition-colors duration-200">Privacy Policy</a>
        </div>
      </div>
    </div>
  </footer>
</template>
