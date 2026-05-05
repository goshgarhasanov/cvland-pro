<script setup lang="ts">
import { RouterLink } from 'vue-router'
</script>

<template>
  <section class="mx-auto flex max-w-2xl flex-col items-center px-6 py-24 text-center">
    <span class="font-display text-7xl font-bold text-brand-600">404</span>
    <h1 class="mt-4 text-2xl font-semibold">Page not found</h1>
    <p class="mt-2 text-slate-500">The page you were looking for has moved or never existed.</p>
    <RouterLink :to="{ name: 'home' }" class="btn-primary mt-6">Back to home</RouterLink>
  </section>
</template>
