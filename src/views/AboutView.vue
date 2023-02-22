<script>
	export default {
		data() {
			return {
				apiContent: '',
			}
		},
		async created() {
			await this.http.get('/api/aboutMsg').then(response => {
				this.apiContent = response.data;
			});
		}
	}
</script>

<template>
	<main class="px-4 mt-8">
		<div class="container mx-auto max-w-full px-4">
			<h3>About</h3>
			<p>{{ apiContent }}</p>
		</div>
	</main>
</template>
