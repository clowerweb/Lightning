<script setup lang="ts">
import { onBeforeMount } from 'vue';
import { useRouter } from 'nuxt/app';

const token = localStorage.getItem('authToken');

defineProps({
  apiInfo: {
    type: Object,
    default: () => ({}),
  },
});

onBeforeMount(() => {
  const router = useRouter();

  if (!token) {
    router.push({ path: '/login' });
  }
});
</script>

<template>
  <div v-if="token" class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Admin Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <!-- Site Statistics -->
      <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Site Statistics</h2>
        <ul>
          <li class="flex justify-between items-center py-2">
            <span class="text-gray-600">Total Users</span>
            <span class="font-bold text-lg">1,234</span>
          </li>
          <li class="flex justify-between items-center py-2">
            <span class="text-gray-600">Page Views</span>
            <span class="font-bold text-lg">56,789</span>
          </li>
          <li class="flex justify-between items-center py-2">
            <span class="text-gray-600">New Signups</span>
            <span class="font-bold text-lg">12</span>
          </li>
        </ul>
      </div>

      <!-- Recent Activity -->
      <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Recent Activity</h2>
        <ul>
          <li class="flex items-center py-2">
            <span class="text-green-500 mr-2">&#9679;</span>
            <span>New user registered: john.doe@example.com</span>
          </li>
          <li class="flex items-center py-2">
            <span class="text-blue-500 mr-2">&#9679;</span>
            <span>Article published: "Getting Started with Lightning"</span>
          </li>
          <li class="flex items-center py-2">
            <span class="text-yellow-500 mr-2">&#9679;</span>
            <span>Comment posted on "Hello World"</span>
          </li>
        </ul>
      </div>

      <!-- System Information -->
      <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">System Information</h2>
        <ul>
          <li class="flex justify-between items-center py-2">
            <span class="text-gray-600">Lightning Version</span>
            <span class="font-bold text-lg">{{ apiInfo.data?.version }}</span>
          </li>
          <li class="flex justify-between items-center py-2">
            <span class="text-gray-600">PHP Version</span>
            <span class="font-bold text-lg">{{ apiInfo.data?.php_version }}</span>
          </li>
          <li class="flex justify-between items-center py-2">
            <span class="text-gray-600">Database Status</span>
            <span class="text-green-500 font-bold">{{ apiInfo.data ? 'Online' : 'Offline' }}</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
