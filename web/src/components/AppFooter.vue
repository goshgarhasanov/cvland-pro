<script setup lang="ts">
import { useI18n } from 'vue-i18n'

const { t } = useI18n()
const year = new Date().getFullYear()
</script>

<template>
  <footer class="border-t border-slate-200 bg-white py-8 dark:border-slate-800 dark:bg-slate-950">
    <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-2 px-6 sm:flex-row">
      <p class="text-sm text-slate-500">© {{ year }} {{ t('app.name') }}. All rights reserved.</p>
      <p class="text-xs text-slate-400">Built with Laravel 11 · Vue 3 · TypeScript</p>
    </div>
  </footer>
</template>
