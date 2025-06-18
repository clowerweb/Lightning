<script setup lang="ts">
import { ref } from 'vue';

const siteName = 'Lightning + Nuxt';
const mobileMenuOpen = ref(false);

const toggleMobileMenu = () => {
  mobileMenuOpen.value = !mobileMenuOpen.value;
};

const closeMobileMenu = () => {
  mobileMenuOpen.value = false;
};
</script>

<template>
  <header class="bg-gray-900 border-b border-lightning-blue/20 shadow-large">
    <div class="container">
      <div class="relative mx-auto px-6 py-2">
        <div class="flex justify-between items-center">
          <!-- Logo -->
          <NuxtLink
            to="/"
            class="flex items-center space-x-3"
          >
            <h1 class="w-[7.5rem]">
              <img src="/assets/img/logo.png" height="47" width="120" alt="Lightning logo" />
              <span class="sr-only">{{ siteName }}</span>
            </h1>
          </NuxtLink>

          <!-- Navigation -->
          <nav class="hidden md:flex space-x-8">
            <NuxtLink to="/about" class="nav-link">
              <span class="flex items-center space-x-2">
                <span>Get Started</span>
              </span>
            </NuxtLink>
          </nav>

          <!-- Mobile menu button -->
          <button
            @click="toggleMobileMenu"
            type="button"
            aria-controls="main-menu-mobile"
            :aria-expanded="mobileMenuOpen"
            aria-label="Main Menu"
            class="md:hidden text-white p-2 rounded-lg hover:bg-white/10 transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
        </div>

        <!-- Mobile menu -->
        <div
          v-if="mobileMenuOpen"
          id="main-menu-mobile"
          class="md:hidden mt-6 pb-4 border-t border-white/20 pt-4"
        >
          <nav class="flex flex-col space-y-4">
            <NuxtLink to="/" class="nav-link" @click="closeMobileMenu">
              <span class="flex items-center space-x-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
                <span>Home</span>
              </span>
            </NuxtLink>
            <NuxtLink to="/about" class="nav-link" @click="closeMobileMenu">
              <span class="flex items-center space-x-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                <span>About</span>
              </span>
            </NuxtLink>
          </nav>
        </div>
      </div>
    </div>
  </header>
</template>

<style scoped lang="postcss">
.nav-link {
  @apply text-white hover:text-lightning-yellow font-medium text-sm transition-colors duration-200;

  &.router-link-exact-active {
    @apply text-lightning-yellow;
  }
}
</style>
