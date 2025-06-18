<script setup lang="ts">
// @ts-ignore
import TheHeader from '../components/TheHeader.vue';
import TheFooter from '../components/TheFooter.vue';
</script>

<template>
  <div class="flex flex-col min-h-screen text-neutral-800">
    <TheHeader />

    <!-- Main content -->
    <main class="flex-grow container">
      <div class="animate-fade-in mb-10">
        <slot />
      </div>
    </main>

    <TheFooter />
  </div>
</template>
