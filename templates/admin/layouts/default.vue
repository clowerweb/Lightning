<template>
  <div class="min-h-screen bg-gray-100">
    <header class="bg-gray-800 text-white shadow-md">
      <nav class="container mx-auto px-4 py-3 flex justify-between items-center">
        <NuxtLink to="/" class="text-xl font-bold hover:text-gray-300">Lightning Admin</NuxtLink>
        <div>
          <NuxtLink to="/" class="px-3 py-2 rounded hover:bg-gray-700">Dashboard</NuxtLink>
          <NuxtLink to="/new" class="px-3 py-2 rounded hover:bg-gray-700">New Page</NuxtLink>
        </div>
      </nav>
    </header>

    <main class="container mx-auto px-4 py-8">
      <slot />
    </main>
  </div>
</template>
