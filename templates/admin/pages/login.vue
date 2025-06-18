<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'nuxt/app';
import { query } from '../utils/graphql';

const username = ref('');
const password = ref('');
const error = ref('');

const login = async () => {
  try {
    const { data, errors } = await query(
      `mutation Login($username: String!, $password: String!) {
        login(username: $username, password: $password) {
          token
          error
        }
      }`,
      {
        username: username.value,
        password: password.value,
      }
    );

    if (errors) {
      throw new Error(errors[0].message);
    }

    if (data.login.token) {
      localStorage.setItem('authToken', data.login.token);
      const router = useRouter();
      router.push({ path: '/' });
    } else {
      throw new Error(data.login.error || 'Invalid credentials');
    }
  } catch (e: any) {
    error.value = e.message;
    console.error(e);
  }
};
</script>

<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md">
      <h1 class="text-2xl font-bold text-center">Admin Login</h1>
      <form @submit.prevent="login">
        <div class="space-y-4">
          <div>
            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
            <input
              id="username"
              v-model="username"
              type="text"
              required
              class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            />
          </div>
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input
              id="password"
              v-model="password"
              type="password"
              required
              class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            />
          </div>
        </div>
        <div class="mt-6">
          <button
            type="submit"
            class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Login
          </button>
        </div>
        <p v-if="error" class="mt-4 text-sm text-center text-red-600">{{ error }}</p>
      </form>
    </div>
  </div>
</template>
