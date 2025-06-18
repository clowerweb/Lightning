<script setup lang="ts">
const scrollToFeatures = () => {
  const element = document.getElementById('features');
  if (element) {
    element.scrollIntoView({ behavior: 'smooth' });
  }
};
</script>

<template>
  <div>
    <!-- Hero Section -->
    <section class="hero-section relative py-20 mb-8 overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-br from-primary-50/80 via-white/50 to-secondary-50/80"></div>
      <div class="relative text-center max-w-4xl mx-auto px-6">
        <div class="animate-fade-in">
          <h1 class="text-6xl md:text-7xl font-display font-bold mb-6">
            <span class="text-gradient">Lightning</span>
            <span class="text-neutral-800"> meets </span>
            <span class="text-gradient">Nuxt</span>
          </h1>
          <p class="text-xl md:text-2xl text-neutral-600 mb-8 leading-relaxed max-w-2xl mx-auto">
            Experience the perfect fusion of <strong class="text-primary-600">Lightning PHP</strong>
            backend power and <strong class="text-accent-600">Vue.js</strong> frontend elegance
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <button @click="scrollToFeatures" class="btn-primary group">
              <span class="flex items-center space-x-2">
                <span>Explore Features</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
              </span>
            </button>
          </div>
        </div>
      </div>

      <!-- Floating elements -->
      <div class="absolute top-20 left-10 w-20 h-20 bg-gradient-to-br from-primary-200 to-primary-300 rounded-full opacity-20 floating-element"></div>
      <div class="absolute bottom-20 right-10 w-16 h-16 bg-gradient-to-br from-secondary-200 to-secondary-300 rounded-full opacity-20 floating-element" style="animation-delay: 1s;"></div>
      <div class="absolute top-1/2 right-20 w-12 h-12 bg-gradient-to-br from-accent-200 to-accent-300 rounded-full opacity-20 floating-element" style="animation-delay: 2s;"></div>
    </section>

    <!-- Features Section -->
    <section id="features" class="mb-16">
      <div class="text-center mb-12">
        <h2 class="text-4xl font-display font-bold text-neutral-800 mb-4">
          Powerful <span class="text-gradient">Features</span>
        </h2>
        <p class="text-lg text-neutral-600 max-w-2xl mx-auto">
          Built with modern technologies and best practices for exceptional performance and developer experience
        </p>
      </div>

      <div class="grid md:grid-cols-3 gap-8">
        <!-- Lightning PHP Card -->
        <div class="card-feature animate-slide-up group" style="animation-delay: 0.1s;">
          <div class="flex items-center space-x-4 mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-primary-400 to-primary-600 rounded-xl flex items-center justify-center shadow-glow group-hover:shadow-glow-secondary transition-all duration-300">
              <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
              </svg>
            </div>
            <h3 class="text-2xl font-display font-semibold text-neutral-800 group-hover:text-primary-600 transition-colors">
              Lightning PHP
            </h3>
          </div>
          <p class="text-neutral-600 leading-relaxed mb-4">
            A lightweight, high-performance PHP framework designed for speed and simplicity.
            Built with modern PHP practices and optimized for rapid development.
          </p>
          <div class="flex flex-wrap gap-2">
            <span class="px-3 py-1 bg-primary-100 text-primary-800 rounded-full text-sm font-medium">Fast</span>
            <span class="px-3 py-1 bg-primary-100 text-primary-800 rounded-full text-sm font-medium">Lightweight</span>
            <span class="px-3 py-1 bg-primary-100 text-primary-800 rounded-full text-sm font-medium">Modern</span>
          </div>
        </div>

        <!-- Nuxt 3 Card -->
        <div class="card-feature animate-slide-up group" style="animation-delay: 0.2s;">
          <div class="flex items-center space-x-4 mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center shadow-glow group-hover:shadow-glow-secondary transition-all duration-300">
              <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="text-2xl font-display font-semibold text-neutral-800 group-hover:text-green-600 transition-colors">
              Nuxt 3
            </h3>
          </div>
          <p class="text-neutral-600 leading-relaxed mb-4">
            The intuitive Vue.js framework with TypeScript support, server-side rendering,
            and automatic code splitting for optimal performance.
          </p>
          <div class="flex flex-wrap gap-2">
            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">Vue 3</span>
            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">TypeScript</span>
            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">SSR</span>
          </div>
        </div>

        <!-- API Integration Card -->
        <div class="card-feature animate-slide-up group" style="animation-delay: 0.3s;">
          <div class="flex items-center space-x-4 mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-accent-400 to-accent-600 rounded-xl flex items-center justify-center shadow-glow group-hover:shadow-glow-secondary transition-all duration-300">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
            <h3 class="text-2xl font-display font-semibold text-neutral-800 group-hover:text-accent-600 transition-colors">
              API Integration
            </h3>
          </div>
          <p class="text-neutral-600 leading-relaxed mb-4">
            Seamless communication between frontend and backend with a GraphQL API,
            real-time data synchronization, and robust error handling.
          </p>
          <div class="flex flex-wrap gap-2 mb-4">
            <span class="px-3 py-1 bg-accent-100 text-accent-700 rounded-full text-sm font-medium">GraphQL</span>
            <span class="px-3 py-1 bg-accent-100 text-accent-700 rounded-full text-sm font-medium">Real-time</span>
            <span class="px-3 py-1 bg-accent-100 text-accent-700 rounded-full text-sm font-medium">Secure</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-gradient-to-r from-primary-50 to-secondary-50 rounded-3xl mb-16">
      <div class="text-center">
        <h2 class="text-3xl font-display font-bold text-neutral-800 mb-12">
          Built for <span class="text-gradient">Performance</span>
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
          <div class="animate-fade-in" style="animation-delay: 0.1s;">
            <div class="text-4xl font-bold text-primary-600 mb-2">⚡</div>
            <div class="text-2xl font-bold text-neutral-800 mb-1">Fast</div>
            <div class="text-sm text-neutral-600">Lightning Speed</div>
          </div>
          <div class="animate-fade-in" style="animation-delay: 0.2s;">
            <div class="text-4xl font-bold text-green-600 mb-2">🚀</div>
            <div class="text-2xl font-bold text-neutral-800 mb-1">Modern</div>
            <div class="text-sm text-neutral-600">Latest Tech Stack</div>
          </div>
          <div class="animate-fade-in" style="animation-delay: 0.3s;">
            <div class="text-4xl font-bold text-accent-600 mb-2">🔒</div>
            <div class="text-2xl font-bold text-neutral-800 mb-1">Secure</div>
            <div class="text-sm text-neutral-600">Enterprise Grade</div>
          </div>
          <div class="animate-fade-in" style="animation-delay: 0.4s;">
            <div class="text-4xl font-bold text-secondary-600 mb-2">📱</div>
            <div class="text-2xl font-bold text-neutral-800 mb-1">Responsive</div>
            <div class="text-sm text-neutral-600">Mobile First</div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
