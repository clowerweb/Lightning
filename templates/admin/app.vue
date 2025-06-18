<script setup lang="ts">
import { onBeforeMount, ref } from 'vue';
import { query } from './utils/graphql';

const apiResult = ref<Record<string, any>>({});
const fetchApiData = async () => {
  const gql = `
    query StatusQuery($controller: String, $method: String, $params: String) {
      status(controller: $controller, method: $method, params: $params) {
        success
        message
        data {
          framework
          version
          timestamp
          php_version
          installed
        }
      }
    }
  `;

  const variables = {
    controller: 'Test',
    method: 'test',
    params: JSON.stringify({ testId: 12345 }),
  };

  try {
    const result = await query(gql, variables);
    apiResult.value = result.data ? result.data.status : result;
  } catch (error) {
    console.error('Error fetching API data:', error);
    apiResult.value = { error: 'Failed to fetch data.', details: error };
  }
};

onBeforeMount(async () => {
  await fetchApiData();
  
  if (!apiResult.value?.data?.installed) {
    window.location.href = '/install.php';
  }
});
</script>

<template>
  <div>
    <NuxtLayout>
      <NuxtPage :api-info="apiResult" />
    </NuxtLayout>
  </div>
</template>
