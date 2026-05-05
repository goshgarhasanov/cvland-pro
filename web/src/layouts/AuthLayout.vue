<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
</script>

<template>
  <div class="flex min-h-screen items-center justify-center bg-gradient-to-br from-brand-50 via-white to-brand-100 p-4 dark:from-slate-950 dark:via-slate-900 dark:to-brand-950">
    <div class="w-full max-w-md">
      <div class="mb-8 text-center">
        <RouterLink to="/" class="inline-flex items-center gap-2 text-2xl font-display font-bold text-brand-700 dark:text-brand-300">
          <span class="grid h-9 w-9 place-items-center rounded-xl bg-brand-600 text-white">CV</span>
          CVLAND <span class="font-light text-brand-400">PRO</span>
        </RouterLink>
      </div>
      <div class="card">
        <RouterView />
      </div>
    </div>
  </div>
</template>
