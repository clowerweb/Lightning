<template>
  <div class="max-w-4xl mx-auto mt-12">
    <!-- Header Section -->
    <div class="text-center mb-16 animate-fade-in">
      <h1 class="text-5xl font-display font-bold mb-6">
        Getting Started
      </h1>
      <p class="text-xl text-neutral-600 leading-relaxed max-w-3xl mx-auto">
        Discover the powerful architecture behind this modern web development template that combines
        the best of PHP backend performance with Vue.js frontend elegance.
      </p>
    </div>

    <!-- Overview Card -->
    <div class="card p-8 mb-12 animate-slide-up">
      <div class="flex items-start space-x-6">
        <div class="w-16 h-16 bg-gradient-to-br from-primary-400 to-primary-600 rounded-2xl flex items-center justify-center shadow-glow flex-shrink-0">
          <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" clip-rule="evenodd" />
          </svg>
        </div>
        <div>
          <h2 class="text-2xl font-display font-bold text-neutral-800 mb-4">Template Overview</h2>
          <p class="text-neutral-600 leading-relaxed text-lg">
            This template showcases a modern <strong class="text-primary-600">decoupled architecture</strong>
            built with Nuxt 3 and TypeScript for the Lightning PHP framework. It demonstrates how to create
            a sophisticated frontend application that communicates seamlessly with a PHP backend through
            well-designed API endpoints.
          </p>
        </div>
      </div>
    </div>

    <!-- Features Grid -->
    <div class="mb-16">
      <h2 class="text-3xl font-display font-bold text-center text-neutral-800 mb-12">
        Key <span class="text-gradient">Features</span>
      </h2>

      <div class="grid md:grid-cols-2 gap-8">
        <div class="card-feature animate-slide-up group" style="animation-delay: 0.1s;">
          <div class="flex items-center space-x-4 mb-4">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold text-neutral-800">Decoupled Architecture</h3>
          </div>
          <p class="text-neutral-600">
            Frontend and backend are completely separate, allowing for independent development,
            scaling, and deployment strategies.
          </p>
        </div>

        <div class="card-feature animate-slide-up group" style="animation-delay: 0.2s;">
          <div class="flex items-center space-x-4 mb-4">
            <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold text-neutral-800">TypeScript Support</h3>
          </div>
          <p class="text-neutral-600">
            Full type safety throughout Vue components with intelligent code completion
            and compile-time error detection.
          </p>
        </div>

        <div class="card-feature animate-slide-up group" style="animation-delay: 0.3s;">
          <div class="flex items-center space-x-4 mb-4">
            <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold text-neutral-800">API Integration</h3>
          </div>
          <p class="text-neutral-600">
            Seamless communication with Lightning PHP API endpoints using modern
            fetch patterns and error handling.
          </p>
        </div>

        <div class="card-feature animate-slide-up group" style="animation-delay: 0.4s;">
          <div class="flex items-center space-x-4 mb-4">
            <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-orange-600 rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold text-neutral-800">Hot Reloading</h3>
          </div>
          <p class="text-neutral-600">
            Lightning-fast development experience with instant updates as you code,
            preserving application state.
          </p>
        </div>

        <div class="card-feature animate-slide-up group" style="animation-delay: 0.5s;">
          <div class="flex items-center space-x-4 mb-4">
            <div class="w-10 h-10 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold text-neutral-800">Production Ready</h3>
          </div>
          <p class="text-neutral-600">
            Optimized build process with code splitting, tree shaking, and
            performance optimizations for production deployment.
          </p>
        </div>

        <div class="card-feature animate-slide-up group" style="animation-delay: 0.6s;">
          <div class="flex items-center space-x-4 mb-4">
            <div class="w-10 h-10 bg-gradient-to-br from-teal-400 to-teal-600 rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold text-neutral-800">Modern Tooling</h3>
          </div>
          <p class="text-neutral-600">
            Built with the latest versions of Vue 3, Nuxt 3, TailwindCSS,
            and modern development tools.
          </p>
        </div>
      </div>
    </div>

    <!-- Getting Started Section -->
    <div class="grid md:grid-cols-2 gap-8 mb-16">
      <div class="card p-8 animate-slide-up" style="animation-delay: 0.1s;">
        <div class="flex items-center space-x-3 mb-6">
          <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-green-500 rounded-lg flex items-center justify-center">
            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
          </div>
          <h3 class="text-2xl font-display font-bold text-neutral-800">Getting Started</h3>
        </div>

        <div class="space-y-4">
          <div class="flex items-start space-x-3">
            <div class="w-6 h-6 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0 mt-0.5">1</div>
            <div>
              <p class="font-medium text-neutral-800">Start Docker</p>
              <p class="text-sm text-neutral-600">Make sure Docker is running on your system</p>
            </div>
          </div>

          <div class="flex items-start space-x-3">
            <div class="w-6 h-6 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0 mt-0.5">2</div>
            <div>
              <p class="font-medium text-neutral-800">Navigate to template</p>
              <code class="text-sm bg-neutral-100 px-2 py-1 rounded text-primary-600">App/templates/default</code>
            </div>
          </div>

          <div class="flex items-start space-x-3">
            <div class="w-6 h-6 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0 mt-0.5">3</div>
            <div>
              <p class="font-medium text-neutral-800">Install dependencies</p>
              <code class="text-sm bg-neutral-100 px-2 py-1 rounded text-primary-600">npm install</code>
            </div>
          </div>

          <div class="flex items-start space-x-3">
            <div class="w-6 h-6 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0 mt-0.5">4</div>
            <div>
              <p class="font-medium text-neutral-800">Start the server</p>
              <code class="text-sm bg-neutral-100 px-2 py-1 rounded text-primary-600">docker-compose up -d</code>
            </div>
          </div>

          <div class="flex items-start space-x-3">
            <div class="w-6 h-6 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0 mt-0.5">5</div>
            <div>
              <p class="font-medium text-neutral-800">Start development</p>
              <p class="text-sm text-neutral-600">Navigate to <code class="text-sm bg-neutral-100 px-2 py-1 rounded text-primary-600">http://lightning.local</code> and get cracking!</p>
            </div>
          </div>
        </div>
      </div>

      <div class="card p-8 animate-slide-up" style="animation-delay: 0.2s;">
        <div class="flex items-center space-x-3 mb-6">
          <div class="w-8 h-8 bg-gradient-to-br from-purple-400 to-purple-500 rounded-lg flex items-center justify-center">
            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </div>
          <h3 class="text-2xl font-display font-bold text-neutral-800">Production Build</h3>
        </div>

        <div class="space-y-4">
          <div class="flex items-start space-x-3">
            <div class="w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0 mt-0.5">1</div>
            <div>
              <p class="font-medium text-neutral-800">Build for production</p>
              <code class="text-sm bg-neutral-100 px-2 py-1 rounded text-purple-600">npm run build</code>
            </div>
          </div>

          <div class="flex items-start space-x-3">
            <div class="w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0 mt-0.5">2</div>
            <div>
              <p class="font-medium text-neutral-800">Files copied to</p>
              <code class="text-sm bg-neutral-100 px-2 py-1 rounded text-purple-600">/public/dist</code>
            </div>
          </div>

          <div class="flex items-start space-x-3">
            <div class="w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0 mt-0.5">3</div>
            <div>
              <p class="font-medium text-neutral-800">Ready for deployment</p>
              <p class="text-sm text-neutral-600">PHP serves optimized build files</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tech Stack -->
    <div class="card p-8 text-center animate-slide-up" style="animation-delay: 0.3s;">
      <h3 class="text-2xl font-display font-bold text-neutral-800 mb-8">
        Built with <span class="text-gradient">Modern Technologies</span>
      </h3>

      <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="group">
          <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </div>
          <h4 class="font-semibold text-neutral-800">TypeScript</h4>
          <p class="text-sm text-neutral-600">Type Safety</p>
        </div>

        <div class="group">
          <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <h4 class="font-semibold text-neutral-800">Vue 3</h4>
          <p class="text-sm text-neutral-600">Composition API</p>
        </div>

        <div class="group">
          <div class="w-16 h-16 bg-gradient-to-br from-teal-400 to-teal-600 rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" clip-rule="evenodd" />
            </svg>
          </div>
          <h4 class="font-semibold text-neutral-800">TailwindCSS</h4>
          <p class="text-sm text-neutral-600">Utility First</p>
        </div>

        <div class="group">
          <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
            </svg>
          </div>
          <h4 class="font-semibold text-neutral-800">Lightning PHP</h4>
          <p class="text-sm text-neutral-600">Fast Backend</p>
        </div>
      </div>
    </div>
  </div>
</template>
