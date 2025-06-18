<script setup lang="ts">
import { ref, onBeforeMount } from 'vue';
import { useRouter } from 'nuxt/app';
import { query } from '../utils/graphql';

const token = localStorage.getItem('authToken');
const router = useRouter();

onBeforeMount(() => {
  if (!token) {
    router.push({ path: '/login' });
  }
});

const pageTitle = ref('');
const pageSlug = ref('');
const pageContent = ref('');

const savePage = async () => {
  if (!pageTitle.value || !pageSlug.value) {
    alert('Title and Slug are required.');
    return;
  }

  const mutation = `
    mutation CreatePage($title: String!, $slug: String!, $content: String!) {
      createPage(title: $title, slug: $slug, content: $content) {
        id
      }
    }
  `;

  const variables = {
    title: pageTitle.value,
    slug: pageSlug.value,
    content: pageContent.value,
  };

  try {
    const response = await query(mutation, variables);

    if (response.errors) {
      console.error('GraphQL Error:', response.errors);
      alert(`Error saving page: ${response.errors[0].message}`);
    } else if (response.data.createPage.id) {
      alert('Page saved successfully!');
      router.push({ path: '/' }); // Redirect to dashboard after saving
    } else {
      alert('An unknown error occurred.');
    }
  } catch (error) {
    console.error('Network or other error:', error);
    alert('An error occurred while trying to save the page.');
  }
};
</script>

<template>
  <div v-if="token" class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Add New Page</h1>

    <div class="bg-white rounded-lg shadow-md p-8">
      <form @submit.prevent="savePage">
        <div class="mb-6">
          <label for="pageTitle" class="block text-gray-700 text-sm font-bold mb-2">Page Title</label>
          <input
            id="pageTitle"
            v-model="pageTitle"
            type="text"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            placeholder="Enter page title"
            required
          />
        </div>

        <div class="mb-6">
          <label for="pageSlug" class="block text-gray-700 text-sm font-bold mb-2">Slug</label>
          <input
            id="pageSlug"
            v-model="pageSlug"
            type="text"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            placeholder="e.g., about-us"
            required
          />
          <p class="text-gray-600 text-xs italic mt-2">The slug is the URL-friendly version of the name.</p>
        </div>

        <div class="mb-6">
          <label for="pageContent" class="block text-gray-700 text-sm font-bold mb-2">Content</label>
          <textarea
            id="pageContent"
            v-model="pageContent"
            rows="10"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
            placeholder="Enter page content here..."
          ></textarea>
        </div>

        <div class="flex items-center justify-end">
          <button
            type="submit"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
          >
            Save Page
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
