<script>
	import { RouterView } from 'vue-router'
	import Header from '@/components/global/Header.vue'

	export default {
		components: {
			Header,
			RouterView
		}
	}
</script>

<template>
	<Header />
  <RouterView />
</template>
