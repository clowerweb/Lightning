<script>
	import { RouterLink } from 'vue-router'

	export default {
		components: {
			RouterLink
		}
	}
</script>

<template>
	<header class="px-4 bg-gray-800">
		<div class="container mx-auto flex items-center max-w-full px-4">
			<h1 class="m-0 p-0 font-bold text-white text-3xl">Lightning</h1>

			<nav class="flex-1 ml-8">
				<ul>
					<li>
						<RouterLink to="/" class="block px-3 py-4 font-bold text-white">
							Home
						</RouterLink>
					</li>
					<li>
						<RouterLink to="/about" class="block px-3 py-4 font-bold text-white">
							About
						</RouterLink>
					</li>
				</ul>
			</nav>
		</div>
	</header>
</template>

<style scoped lang="postcss">
	ul {
		> li {
			display: inline-block;

			> a {
				transition: color .3s ease-in-out;

				&.router-link-exact-active {
					color: #f00;
					position: relative;

					&:after {
						background: #f00;
						bottom: 0;
						content: '';
						height: 4px;
						left: 0;
						position: absolute;
						right: 0;
					}
				}

				&:active,
				&:focus,
				&:hover {
					color: #f00;
				}
			}
		}
	}
</style>
